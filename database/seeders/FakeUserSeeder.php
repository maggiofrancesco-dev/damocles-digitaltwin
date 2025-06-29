<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FakeUser;
use App\Models\User;
use Illuminate\Support\Arr;
use Faker\Factory as Faker;

class FakeUserSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create();

        // Ensure at least one user exists as evaluator
        $evaluator = User::where('role', 'Evaluator')->inRandomOrder()->first();

        if (!$evaluator) {
            $evaluator = User::factory()->create(['role' => 'Evaluator']);
        }

        $humanFactorsList = [
            'Impulsivity', 'Overconfidence', 'Lack of Awareness', 'Stress', 'Risk attitude',
            'Distraction', 'Fatigue', 'Shame', 'Complacency', 'Agreeableness'
        ];

        foreach (range(1, 20) as $i) {
            $randomFactors = Arr::random($humanFactorsList, rand(2, 4));
            $humanFactors = [];

            foreach ($randomFactors as $factor) {
                $humanFactors[$factor] = rand(1, 3); // 1 = Low, 3 = High
            }

            FakeUser::create([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'gender' => $faker->randomElement(['Male', 'Female', 'Other']),
                'dob' => $faker->dateTimeBetween('-65 years', '-18 years')->format('Y-m-d'),
                'company_role' => $faker->jobTitle,
                'human_factors' => $humanFactors,
                'evaluator_id' => $evaluator->id,
            ]);
        }
    }
}

