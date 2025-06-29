<?php

namespace App\Http\Controllers;

use App\Models\FakeUser;
use App\Models\HumanFactor;
use Carbon\Carbon;
use Illuminate\Http\Request;

/**
 * Author: Gioele Giannico, Francesco Baldi
 */

class FakeUserController extends Controller
{
    public function create(Request $request)
    {
        $eighteenYearsAgo = Carbon::now()->subYears(18)->toDateString();
        $hundredYearsAgo = Carbon::now()->subYears(100)->toDateString();


        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'surname' => ['required', 'string', 'max:255'],
            'gender' => ['required', 'in:Male,Female,Other'],
            'dob' => ['required', 'date', 'before_or_equal:' . $eighteenYearsAgo, 'after_or_equal:' . $hundredYearsAgo],
            'company_role' => ['required', 'string', 'max:255'],
            'human_factors' => ['nullable', 'array'],
            'human_factors.*.enabled' => ['nullable'],
            'human_factors.*.value' => ['nullable', 'integer', 'min:1', 'max:5'],
        ], [
            'dateOfBirth.before_or_equal' => 'User must be at least 18 years old.',
            'dateOfBirth.after_or_equal' => 'User must be younger than 100 years old.',
        ]);

        // Transform human factors into clean array
        $humanFactors = [];
        if (!empty($validated['human_factors'])) {
            foreach ($validated['human_factors'] as $factorName => $data) {
                if (!empty($data['enabled'])) {
                    $value = (int) ($data['value'] ?? 3);
                    $factor = HumanFactor::where('factor_name', $factorName)->first();
                    if ($factor) {
                        $humanFactors[$factor->id] = ['value' => $value];
                    }
                }
            }
        }

        // Create the FakeUser without the old 'human_factors' array
        $fakeUser = FakeUser::create([
            'name' => $validated['name'],
            'surname' => $validated['surname'],
            'gender' => $validated['gender'],
            'dob' => $validated['dob'],
            'company_role' => $validated['company_role'],
            'evaluator_id' => auth()->id(),
        ]);

        // Attach human factors to the pivot table
        if (!empty($humanFactors)) {
            $fakeUser->humanFactors()->attach($humanFactors);
        }

        return redirect()->back()->with('success', __('Digital twin created successfully.'));
    }

    public function destroy(int $fakeUserId)
    {
        $digitalTwin = FakeUser::findOrFail($fakeUserId);

        $digitalTwin->delete();

        return redirect()->back()->with('success', 'Fake user deleted successfully!');
    }
}
