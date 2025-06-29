<?php

/**
 * Author: Gioele Giannico
 */

use App\Models\User;
use App\Models\DigitalTwin;
use App\Models\HumanFactor;
use Illuminate\Support\Facades\Session;

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

test('evaluator can view digital twin index', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    DigitalTwin::factory()->count(2)->create(['evaluator_id' => $evaluator->id]);

    $response = $this->actingAs($evaluator)->get(route('digital-twin.index'));

    $response->assertStatus(200)
             ->assertSee('Digital Twin')
             ->assertSee(DigitalTwin::first()->name);
});

test('starting new twin clears draft and shows form', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    Session::put('digital_twin_draft', [
        'name' => 'test',
        'surname' =>  'test',
        'dateOfBirth' => '1980-01-01',
        'role' => 'role',
        'gender' => 'Male',
        'prompt' => 'test prompt'
    ]);

    $response = $this->actingAs($evaluator)->get(route('digital-twin.select-users'));

    $response->assertStatus(200)
             ->assertViewHas('prompt');
});

test('evaluator can delete a digital twin', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    $twin = DigitalTwin::factory()->create(['evaluator_id' => $evaluator->id]);

    $response = $this->actingAs($evaluator)
                     ->delete(route('digital-twin.destroy', $twin->id));

    $response->assertRedirect()
             ->assertSessionHas('success', 'Digital twin deleted successfully!');
    $this->assertDatabaseMissing('digital_twins', ['id' => $twin->id]);
});
