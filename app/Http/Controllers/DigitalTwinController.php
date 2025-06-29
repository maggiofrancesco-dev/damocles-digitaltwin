<?php

namespace App\Http\Controllers;

use App\Helpers\HumanFactorHelper;
use App\Models\DigitalTwin;
use App\Models\DigitalTwinsPrompt;
use App\Models\FakeUser;
use App\Models\HumanFactor;
use App\Models\LLM;
use App\Models\User;
use App\Services\JsonExtractor;
use Carbon\Carbon;
use Exception;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

/**
 * Author: Gioele Giannico, Francesco Baldi
 */

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
        $hundredYearsAgo = Carbon::now()->subYears(100)->toDateString();
        
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'dateOfBirth' => ['required', 'date', 'before_or_equal:' . $eighteenYearsAgo, 'after_or_equal:' . $hundredYearsAgo],
            'gender' => ['required', 'string', 'in:Male,Female,Other'],
            'companyRole' => ['required', 'string', 'max:255'],
            'prompt' => ['required', 'string'],
        ], [
            'dateOfBirth.before_or_equal' => 'User must be at least 18 years old.',
            'dateOfBirth.after_or_equal' => 'User must be younger than 100 years old.',
        ]);

        
        $age = Carbon::parse($validated['dateOfBirth'])->age;

        $user = [
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'age' => $age,
            'role' => $validated['companyRole'],
            'gender' => $validated['gender']
        ];

        $validated['prompt'] = $this->generateDigitalTwinPrompt($validated['prompt'], $user, collect());

        session(['digital_twin_draft' => $validated]); // not flash, so persists beyond next request

        return redirect()->route('digital-twin.fake-users');
    }

    public function fakeUsers()
    {
        $user = auth()->user();

        $fakeUsers = [];

        if ($user->role === 'Evaluator') {
            $fakeUsers = FakeUser::where('evaluator_id', $user->id)->get();
        }
        
        $prompt = session('digital_twin_draft')['prompt'];

        $allHumanFactors = HumanFactor::pluck('factor_name');


        return view('digital-twin.choose-fake-users')->with([
            'fakeUsers' => $fakeUsers,
            'prompt' => $prompt,
            'allHumanFactors' => $allHumanFactors
        ]);
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

    /**
     * Generate a digital twin prompt using personal characteristics.
     *
     * @param string $prompt
     * @param array $user [
     *     'name' => string,
     *     'surname' => string,
     *     'age' => int,
     *     'role' => string,
     *     'gender' => string
     * ]
     * @param \Illuminate\Support\Collection $additionalHumanFactors Collection of HumanFactor models with pivot->value
     * @return string
     */
    private function generateDigitalTwinPrompt(string $prompt, array $user, Collection $additionalHumanFactors): string
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
        if ($additionalHumanFactors->isNotEmpty()) {
            $lines = $additionalHumanFactors->map(function ($factor) {
                return "{$factor->name}: Risk level {$factor->pivot->value} out of 5";
            })->toArray();

            $humanFactorsString = "\n\nOther vulnerable human factors of this user are:\n" . implode("\n", $lines);
        }

        return $filled . $humanFactorsString;
    }

    public function create(Request $request)
    {
        $evaluatorId = auth()->id();

        $validated = $request->validate([
            'selected_users' => 'required|array|min:1',
            'selected_users.*' => 'integer|exists:users,id',
        ]);

        $humanFactors = collect([]);

        foreach ($validated['selected_users'] as $userId) {
            $user = User::findOrFail($userId);
            $humanFactors = HumanFactorHelper::mergeHumanFactors($humanFactors, $user->humanFactors);
        }

        $prompt = session('digital_twin_draft')['prompt'];

        $digitalTwinDraft = session('digital_twin_draft');

        $prompt = $this->generateDigitalTwinPrompt($prompt, [], $humanFactors);        

        $digitalTwin = DigitalTwin::create([
            'name' => $digitalTwinDraft['name'],
            'surname' => $digitalTwinDraft['surname'],
            'dob' => $digitalTwinDraft['dateOfBirth'],
            'gender' => $digitalTwinDraft['gender'],
            'company_role' => $digitalTwinDraft['companyRole'],
            'prompt' => $prompt,
            'evaluator_id' => $evaluatorId,
        ]);

        // Attach human factors with pivot value
        $pivotData = $humanFactors->mapWithKeys(function ($factor) {
            return [$factor->id => ['value' => $factor->pivot->value]];
        });

        $digitalTwin->humanFactors()->attach($pivotData);

        session()->forget('digital_twin_draft');

        return redirect()->route('digital-twin.index')->with('success', 'Digital Twin created successfully!');
    }


    public function details(int $digitalTwinId)
    {
        $digitalTwin = DigitalTwin::findOrFail($digitalTwinId);

        return view('digital-twin.digital-twin-details', compact('digitalTwin'));
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
