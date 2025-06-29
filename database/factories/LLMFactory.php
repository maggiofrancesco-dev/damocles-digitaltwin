<?php

namespace Database\Factories;

use App\Models\LLM;
use Illuminate\Database\Eloquent\Factories\Factory;

class LLMFactory extends Factory
{
    protected $model = LLM::class;

    public function definition()
    {
        return [
            // A unique URL or identifier for the LLM endpoint
            'endpoint' => $this->faker->unique()->url(),

            // Example providers: 'openai', 'anthropic', 'azure'
            'provider' => $this->faker->randomElement(['openai', 'anthropic', 'azure']),

            // Model names like 'gpt-4', 'claude-v1', etc.
            'model'    => $this->faker->randomElement([
                'gpt-3.5-turbo',
                'gpt-4',
                'claude-v1',
                'azure-openai-gpt4'
            ]),
        ];
    }
}
