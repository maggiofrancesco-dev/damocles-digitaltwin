<?php

namespace Database\Factories;

use App\Models\HumanFactor;
use Illuminate\Database\Eloquent\Factories\Factory;

class HumanFactorFactory extends Factory
{
    protected $model = HumanFactor::class;

    public function definition()
    {
        return [
            // The unique internal name/key for this factor
            'factor_name' => $this->faker->unique()->word(),
            // A human-friendly label or description
        ];
    }
}
