<?php

use App\Models\User;
use App\Models\FakeUser;
use App\Models\HumanFactor;
use Carbon\Carbon;

/**
 * Author: Francesco Baldi
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


test('evaluator can create fake user with human factor', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    $factor = HumanFactor::first();
    $dob = Carbon::now()->subYears(25)->toDateString();

    $response = $this->actingAs($evaluator)->post(route('fake-user.create'), [
        'name' => 'Luca',
        'surname' => 'Bianchi',
        'gender' => 'Male',
        'dob' => $dob,
        'company_role' => 'Analyst',
        'human_factors' => [
            $factor->factor_name => ['enabled' => 'on', 'value' => 4]
        ],
    ]);

    $response->assertRedirect()
             ->assertSessionHas('success');
    $this->assertDatabaseHas('fake_users', ['name' => 'Luca', 'surname' => 'Bianchi']);
    $this->assertDatabaseHas('fake_user_human_factor', ['human_factor_id' => $factor->id, 'value' => 4]);
});

test('evaluator can delete fake user', function () {
    $evaluator = User::where('role', 'Evaluator')->first();
    $fake = FakeUser::factory()->create(['evaluator_id' => $evaluator->id]);

    $response = $this->actingAs($evaluator)
                     ->delete(route('fake-user.destroy', $fake->id));

    $response->assertRedirect()
             ->assertSessionHas('success', 'Fake user deleted successfully!');
    $this->assertDatabaseMissing('fake_users', ['id' => $fake->id]);
});
