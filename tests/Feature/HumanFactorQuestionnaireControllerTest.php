<?php

use App\Models\User;
use App\Models\HumanFactor;
use Illuminate\Foundation\Testing\RefreshDatabase;
/**
 * Author: Davide Viccari
 */

beforeEach(function () {
    // Create one User and one Evaluator
    User::factory()->create([
        'name'     => 'TestUser',
        'surname'  => 'One',
        'email'    => 'user@example.com',
        'password' => bcrypt('password'),
        'role'     => 'User',
    ]);
    User::factory()->create([
        'name'     => 'TestEval',
        'surname'  => 'One',
        'email'    => 'eval@example.com',
        'password' => bcrypt('password'),
        'role'     => 'Evaluator',
    ]);

    // Ensure at least one HumanFactor exists for scoring
    HumanFactor::factory()->count(3)->create();
});

test('questionnaire form can be rendered', function () {
    $user = User::where('role', 'User')->first();

    $response = $this->actingAs($user)
                     ->withoutMiddleware()
                     ->get(route('hf.questionnaire'));

    $response->assertStatus(200)
             ->assertViewIs('auth.hf-questionnaire')
             ->assertViewHas('data');
});

test('user without completed hf questionnaire is redirected to questionnaire page', function () {
    $user = User::where('role', 'User')->first();

    // Simula un utente autenticato che NON ha ancora completato il questionnaire
    $response = $this
        ->actingAs($user)
        // non bypassiamo il middleware qui!
        ->get('/dashboard');

    $response->assertRedirect(route('hf.questionnaire'));
});