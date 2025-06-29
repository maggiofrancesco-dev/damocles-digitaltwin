<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use App\Models\User;
use App\Models\HumanFactor;
use Illuminate\Support\Facades\Auth;

class HumanFactorQuestionnaireController extends Controller
{
    public function create(): View
    {
        $data = json_decode(file_get_contents(resource_path('json/human_factors_questions.json')), true);
        return view('auth.hf-questionnaire', compact('data'));
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'question1' => ['required', 'integer', 'between:0,2'],
            'question2' => ['required', 'integer', 'between:0,2'],
            'question3' => ['required', 'integer', 'between:0,2'],
            'question4' => ['required', 'integer', 'between:0,2'],
            'question5' => ['required', 'integer', 'between:0,2'],
            'question6' => ['required', 'integer', 'between:0,2'],
            'question7' => ['required', 'integer', 'between:0,2'],
            'question8' => ['required', 'integer', 'between:0,2'],
            'question9' => ['required', 'integer', 'between:0,2'],
            'question10' => ['required', 'integer', 'between:0,2'],
            'question11' => ['required', 'integer', 'between:0,2'],
            'question12' => ['required', 'integer', 'between:0,2'],
            'question13' => ['required', 'integer', 'between:0,2'],
            'question14' => ['required', 'integer', 'between:0,2'],
            'question15' => ['required', 'integer', 'between:0,2'],
        ]);

        $data = json_decode(file_get_contents(resource_path('json/human_factors_questions.json')), true);

        // Get answers from user input and remove the csrf token
        $answers = collect(array_slice($request->all(), 1))->values()->all();

        // Cycle through all question response from the user
        for ($i = 0; $i < count($data['questions']); $i++) {
            // Convert response to integer
            $input_answer = (int) $answers[$i];

            // Retrieve answer data (text and human factor scores)
            $answer_data = $data['questions'][$i]['answers'][$input_answer];

            // Get Human Factor Scores
            $hf_scores = $answer_data["scores"];

            $user = User::find(Auth::user()->id);

            // For each hf_score create an entry in the pivot table and associate the current user and its value
            foreach(array_keys($hf_scores) as $hf_key) {
                $humanFactorId = HumanFactor::where('factor_name', $hf_key)->value('id');
                $user->humanFactors()->attach($humanFactorId, [
                    'value' => $hf_scores[$hf_key],
                ]);
            }
        }

        
        // Check if the user is a User 
        if ($user->role === 'User') {
            return redirect()->intended(route('questionnaires-campaign.index', absolute: false));
        } else {
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }
}
