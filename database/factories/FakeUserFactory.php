<?php

namespace Database\Factories;

use App\Models\FakeUser;
use Illuminate\Database\Eloquent\Factories\Factory;
use Carbon\Carbon;

class FakeUserFactory extends Factory
{
    protected $model = FakeUser::class;

    public function definition()
    {
        $dob = $this->faker->dateTimeBetween('-80 years', '-18 years');
        return [
            'name'         => $this->faker->firstName,
            'surname'      => $this->faker->lastName,
            'gender'       => $this->faker->randomElement(['Male','Female','Other']),
            'dob'          => $dob->format('Y-m-d'),
            'company_role' => $this->faker->jobTitle,
            'evaluator_id' => \App\Models\User::factory()->state(['role'=>'Evaluator']),
        ];
    }

    /**
     * Aggancia alcuni human factor di esempio al fake user
     */
    public function withHumanFactors(array $factors = [])
    {
        return $this->afterCreating(function (FakeUser $user) use ($factors) {
            // $factors = [ humanFactorId => value, ... ]
            foreach ($factors as $id => $value) {
                $user->humanFactors()->attach($id, ['value' => $value]);
            }
        });
    }
}
