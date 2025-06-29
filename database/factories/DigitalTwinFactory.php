<?php

namespace Database\Factories;

use App\Models\DigitalTwin;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DigitalTwinFactory extends Factory
{
    protected $model = DigitalTwin::class;

    public function definition()
    {
        $dob = $this->faker->dateTimeBetween('-80 years', '-18 years');
        return [
            'name'         => $this->faker->firstName,
            'surname'      => $this->faker->lastName,
            'dob'          => $dob->format('Y-m-d'),
            'gender'       => $this->faker->randomElement(['Male','Female','Other']),
            'company_role' => $this->faker->jobTitle,
            'prompt'       => $this->faker->paragraph,
            'evaluator_id' => \App\Models\User::factory()->state(['role'=>'Evaluator']),
            // se vuoi associare humanFactors, usalo in un afterCreating callback nel test o in uno stato personalizzato
        ];
    }
}
