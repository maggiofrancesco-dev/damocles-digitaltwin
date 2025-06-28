<?php

namespace App\Http\Controllers;

use App\Helpers\HumanFactorHelper;
use App\Models\DigitalTwin;
use App\Models\DigitalTwinsPrompt;
use App\Models\FakeUser;
use App\Models\LLM;
use App\Models\User;
use App\Services\JsonExtractor;
use Carbon\Carbon;
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

        session()->forget('digital_twin_draft');

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
        $eighteenYearsAgo = Carbon::now()->subYears(18)->toDateString();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'dateOfBirth' => ['required', 'date', 'before_or_equal:' . $eighteenYearsAgo],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'companyRole' => ['required', 'string', 'max:255'],
            'prompt' => ['required', 'string'],
        ], [
            'dateOfBirth.before_or_equal' => 'User must be at least 18 years old.',
        ]);

        $age = Carbon::parse($validated['dateOfBirth'])->age;

        $user = [
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'age' => $age,
            'role' => $validated['companyRole'],
            'gender' => $validated['gender']
        ];

        $validated['prompt'] = $this->generateDigitalTwinPrompt($validated['prompt'], $user, []);

        session(['digital_twin_draft' => $validated]); // not flash, so persists beyond next request

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

        $prompt = session('digital_twin_draft')['prompt'];


        return view('digital-twin.choose-fake-users')->with([
            'fakeUsers' => $fakeUsers,
            'prompt' => $prompt,
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

    public function selectUsers()
    {
        $users = [];

        $prompt = session('digital_twin_draft')['prompt'];

        $users = User::where('role', 'User')->get();


        return view('digital-twin.select-users')->with([
            'users' => $users,
            'prompt' => $prompt,
        ]);
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

    /**
    * Generate a digital twin prompt using personal characteristics.
    *
    * @param array $user [
    *     'name' => string,
    *     'surname' => string,
    *     'age' => int,
    *     'role' => string,
    *     'gender' => string
    * ]
    * @param array $additionalHumanFactors [
    *      string => int
    * ]
    * @return string
    */
    private function generateDigitalTwinPrompt(string $prompt, array $user, array $additionalHumanFactors): string
    {   
        // Replace placeholders with real values
        $filled = str_replace(
            ['{{name}}', '{{surname}}', '{{age}}', '{{role}}', '{{gender}}'],
            [
                $user['name'] ?? 'N/A',
                $user['surname'] ?? 'N/A',
                $user['age'] ?? 'N/A',
                $user['role'] ?? 'N/A',
                $user['gender'] ?? 'N/A'
            ],
            $prompt
        );

        $humanFactorsString = '';
        if (!empty($additionalHumanFactors)) {
            $lines = array_map(function ($trait, $level) {
                return "{$trait}: Risk level {$level} out of 5";
            }, array_keys($additionalHumanFactors), $additionalHumanFactors);

            $humanFactorsString = "\n\nOther vulnerable human factors of this user are:\n" . implode("\n", $lines);
        }
    
        return $filled . $humanFactorsString;
    }

    public function create(Request $request)
    {
        $evaluator = auth()->user();

        $validated = $request->validate([
            'selected_users' => 'required|array',
            'selected_users.*' => 'integer|exists:users,id',
        ]);

        $humanFactors = [];

        foreach ($validated['selected_users'] as $userId) {
            $user = User::findOrFail($userId);
            $humanFactors = HumanFactorHelper::mergeHumanFactors($humanFactors, $user->human_factors);
        }

        $prompt = session('digital_twin_draft')['prompt'];

        $digitalTwin = session('digital_twin_draft');

        $prompt = $this->generateDigitalTwinPrompt($prompt, [], $humanFactors);        

        DigitalTwin::create([
            'name' => $digitalTwin['name'],
            'surname' => $digitalTwin['surname'],
            'dob' => $digitalTwin['dateOfBirth'],
            'gender' => $digitalTwin['gender'],
            'company_role' => $digitalTwin['companyRole'],
            'human_factors' => $humanFactors,
            'prompt' => $prompt,
            'evaluator_id' => $evaluator->id,
        ]);

        session()->forget('digital_twin_draft');

        return redirect()->route('digital-twin.index')->with('success', 'Digital Twin created successfully!');
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
