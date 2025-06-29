<?php

use App\Models\User;
use App\Models\LLM;
use App\Models\DigitalTwin;
use App\Models\EthicalPhishingCampaign;
use App\Models\HumanFactor;

/**
 * Author: Francesco Maggio
 */

beforeEach(function () {
    // Creo l’evaluator
    $evaluator = User::factory()->create([
        'name'      => 'Mario',
        'surname'   => 'Rossi',
        'email'     => 'evaluator@example.com',
        'dob'       => '1980-01-01',
        'password'  => bcrypt('password'),
        'role'      => 'Evaluator',
    ]);

    // Creo 3 human factor e li attacco al valutatore con valori random
    $factors = HumanFactor::factory()->count(3)->create();
    foreach ($factors as $factor) {
        $evaluator->humanFactors()->attach($factor->id, [
            'value' => rand(1, 5),
        ]);
    }
});

test('evaluator can view phishing campaign index', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    EthicalPhishingCampaign::factory()->count(2)->create(['evaluator_id' => $evaluator->id]);

    $response = $this->actingAs($evaluator)->get(route('ethical-phishing-campaign.index'));

    $response->assertStatus(200)
             ->assertSee('Phishing Campaign');
});

test('evaluator can create campaign and is redirected to select-users', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    $llm = LLM::factory()->create();

    $response = $this->actingAs($evaluator)->post(route('ethical-phishing-campaign.create'), [
        'title' => 'Test Phish',
        'description' => 'Desc',
        'llm' => $llm->id,
        'subject' => 'Hello',
        'content' => 'Email body',
    ]);

    $campaign = EthicalPhishingCampaign::first();
    $response->assertRedirect(route('ethical-phishing-campaign.select-users', ['phishingCampaign' => $campaign->id]))
             ->assertSessionHas('success', 'Campaign created.');
});

test('evaluator can delete a phishing campaign', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    $campaign = EthicalPhishingCampaign::factory()->create(['evaluator_id' => $evaluator->id]);

    $response = $this->actingAs($evaluator)
                     ->delete(route('ethical-phishing-campaign.destroy', $campaign->id));

    $response->assertRedirect()
             ->assertSessionHas('success', 'Campaign deleted successfully!');
    $this->assertDatabaseMissing('ethical_phishing_campaigns', ['id' => $campaign->id]);
});
