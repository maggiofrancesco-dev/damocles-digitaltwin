<?php

namespace Database\Seeders;

use App\Models\HumanFactor;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Faker\Factory as Faker;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();
        $users = [];

        // Admin and Evaluator users
        $users[] = [
            'name' => 'Admin',
            'surname' => 'Admin',
            'gender' => 'Male',
            'dob' => '1970-01-01',
            'email' => 'admin@damocles.com',
            'password' => Hash::make('secretAdmin'),
            'role' => 'Admin',
            'company_role' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];

        $users[] = [
            'name' => 'Evaluator',
            'surname' => 'Evaluator',
            'gender' => 'Male',
            'dob' => '1970-01-01',
            'email' => 'evaluator@damocles.com',
            'password' => Hash::make('secretEvaluator'),
            'role' => 'Evaluator',
            'company_role' => null,
            'created_at' => now(),
            'updated_at' => now()
        ];

        // Random users
        // Uncomment to generate them randomically
        $genders = ['Male', 'Female', 'Other'];

        // Fetch all human factors from the DB
        $allHumanFactors = HumanFactor::pluck('id')->all();

        for ($i = 0; $i < 10; $i++) {
            $gender = $genders[array_rand($genders)];
            $dob = $faker->dateTimeBetween('-80 years', '-18 years')->format('Y-m-d');

            $userId = DB::table('users')->insertGetId([
                'name' => $faker->firstName,
                'surname' => $faker->lastName,
                'gender' => $gender,
                'dob' => $dob,
                'email' => 'user' . $i . '@damocles.com',
                'password' => Hash::make('secretUser'),
                'role' => 'User',
                'company_role' => 'Employee',
                'created_at' => now(),
                'updated_at' => now()
            ]);

            // Assign 2–4 random human factors
            $assignedFactors = collect($allHumanFactors)->random(rand(2, 4));
            foreach ($assignedFactors as $factorId) {
                DB::table('human_factor_user')->insert([
                    'user_id' => $userId,
                    'human_factor_id' => $factorId,
                    'value' => rand(1, 5), // Low (1) to High (5)
                    'created_at' => now(),
                    'updated_at' => now()
                ]);
            }
        }
        
        DB::table('users')->insert($users);
    }
}
