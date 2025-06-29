<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LLMsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('llms')->insert([
            [
                'endpoint' => 'http://g4f:8080/v1/chat/completions',
                'provider' => 'Free2GPT',
                'model' => 'gemini-1.5-flash',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
