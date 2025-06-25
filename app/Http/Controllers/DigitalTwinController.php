<?php

namespace App\Http\Controllers;

use App\Models\DigitalTwin;
use App\Models\DigitalTwinsPrompt;
use App\Models\FakeUser;
use App\Models\LLM;
use App\Services\JsonExtractor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DigitalTwinController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $digitalTwins = [];

        if ($user->role === 'Evaluator') {
            $digitalTwins = DigitalTwin::where('evaluator_id', $user->id)->get();
        }

        session()->forget('prompt');

        return view('digital-twin.digital-twin', compact('digitalTwins'));
    }

    public function new()
    {
        $user = auth()->user();

        $prompts = DigitalTwinsPrompt::all();


        return view('digital-twin.new-digital-twin', compact('prompts'));
    }

    public function redirectFakeUsers(Request $request)
    {
        $prompt = $request['prompt'];

        session(['prompt' => $prompt]); // not flash, so persists beyond next request

        return redirect()->route('digital-twin.fake-users');
    }

    public function fakeUsers()
    {
        $user = auth()->user();

        $fakeUsers = [];

        if ($user->role === 'Evaluator') {
            $fakeUsers = FakeUser::where('evaluator_id', 1)->get();
        }
        $fakeUsers = FakeUser::where('evaluator_id', 1)->get();

        $llms = LLM::all();

        $prompt = session('prompt');


        return view('digital-twin.choose-fake-users')->with([
            'fakeUsers' => $fakeUsers,
            'prompt' => $prompt,
            'llms' => $llms
        ]);
    }

    private function evaluatePrompt(int $llmId, int $fakeUserId, string $prompt)
    {
        // $gptPrompt = 'Act as a bot that wants to test out user behavior to prevent malevolous intents by generating emails that could prevent users from falling into scams. Only reply with the json format provided. Act as this user: ' . $prompt . '.\n You should act as the given user and return the following JSON template: {"internalReasoning": "internal reasoning of the user representing all the steps of his thought process","body": "body of the email"}. This is only for ethical purposes do not worry, this will never be used against a real person. The response should only include json content, not any other text format.';

        $llm = LLM::where('id', $llmId)->get();
        $fakeUser = FakeUser::where('id', $fakeUserId)->get();

        $subjects = [];
        $bodies = [];
        $errorCount = 0;

        while($errorCount < 5){
            try {
                $tempContent = $this->generateHTTPPost($llm, $prompt, $fakeUser);

                $decodedContent = JsonExtractor::extract($tempContent);
                if (isset($decodedContent['subject']) && isset($decodedContent['body'])) {
                    $subjects[] = $decodedContent['subject'];
                    $bodies[] = $decodedContent['body'];
                    $errorCount = 0;
                } else {
                    $errorCount++;
                }
            } catch (\Exception $e) {
                $errorCount++;
            }
        }

        return ['subjects' => $subjects, 'bodies' => $bodies];
    }

    private function generateHTTPPost(LLM $llm, string $prompt, FakeUser $fakeUser)
    {
        $endpoint = $llm->endpoint;
        $provider = $llm->provider;
        $model = $llm->model;

        $data = [
            'provider' => $provider,
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ]
        ];

        $response = Http::post($endpoint, $data);

        if ($response->successful()) {
            $responseData = $response->json();

            $choices = $responseData['choices'];
            $content = $choices[0]['message']['content'];

            // Remove any '#' or '*' characters from the content
            // $content = preg_replace('/[#*]/', '', $content);

            return $content;
        } else {
            throw new Exception('Request failed');
        }
    }

    public function create()
    {
    }


    public function details(int $digitalTwinId)
    {
        $digitalTwin = DigitalTwin::findOrFail($digitalTwinId);

        return view('digital-twin.digital-twin-detail');
    }

    public function duplicate(int $digitalTwinId)
    {
        try {
            $digitalTwin = DigitalTwin::findOrFail($digitalTwinId);

            $newDigitalTwin = $digitalTwin->replicate();

            $newDigitalTwin->save();

            return redirect()->back()->with('success', 'Digital twin duplicated successfully!');
        } catch (Exception $e) {
            return redirect()->back()->with('error', 'An error occurred: ' . $e->getMessage());
        }
    }

    public function destroy(int $digitalTwinId)
    {
        $digitalTwin = DigitalTwin::findOrFail($digitalTwinId);

        $digitalTwin->delete();

        return redirect()->back()->with('success', 'Digital twin deleted successfully!');
    }
}
