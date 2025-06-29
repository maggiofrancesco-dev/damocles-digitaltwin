<?php

namespace Database\Factories;

use App\Models\EthicalPhishingCampaign;
use Illuminate\Database\Eloquent\Factories\Factory;

class EthicalPhishingCampaignFactory extends Factory
{
    protected $model = EthicalPhishingCampaign::class;

    public function definition()
    {
        return [
            'title'        => $this->faker->sentence(4),
            'description'  => $this->faker->paragraph,
            'state'        => 'Draft',
            'subject'      => $this->faker->sentence(3),
            'content'      => $this->faker->paragraph(2),
            'evaluator_id' => \App\Models\User::factory()->state(['role'=>'Evaluator']),
            'llm_id'       => \App\Models\LLM::factory(),
        ];
    }

    /**  
     * Stato pronto per lanciare la campagna  
     */
    public function ready()
    {
        return $this->state(['state'=>'Ready']);
    }
}
