<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class HumanFactorSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        DB::table('human_factors')->insert([
        [
            'factor_name' => 'Age',
        ],
        [
            'factor_name' => 'Agreeableness',
        ],
        [
            'factor_name' => 'Anxiousness',
        ],
        [
            'factor_name' => 'Attitude toward policies',
        ],
        [
            'factor_name' => 'Bias',
        ],
        [
            'factor_name' => 'Calmness',
        ],
        [
            'factor_name' => 'Cognitive fatigue',
        ],
        [
            'factor_name' => 'Complacency',
        ],
        [
            'factor_name' => 'Compulsive behaviour',
        ],
        [
            'factor_name' => 'Conscientiousness',
        ],
        [
            'factor_name' => 'Distraction',
        ],
        [
            'factor_name' => 'Emotional stability',
        ],
        [
            'factor_name' => 'Extroversion',
        ],
        [
            'factor_name' => 'Fatigue',
        ],
        [
            'factor_name' => 'Fear',
        ],
        [
            'factor_name' => 'Frustration',
        ],
        [
            'factor_name' => 'Gender',
        ],
        [
            'factor_name' => 'Impulsivity',
        ],
        [
            'factor_name' => 'Internet addiction',
        ],
        [
            'factor_name' => 'Lack of Awareness',
        ],
        [
            'factor_name' => 'Lack of Knowledge',
        ],
        [
            'factor_name' => 'Lack of Resources',
        ],
        [
            'factor_name' => 'Lack of communication',
        ],
        [
            'factor_name' => 'Lack of trust',
        ],
        [
            'factor_name' => 'Laziness',
        ],
        [
            'factor_name' => 'Misperception',
        ],
        [
            'factor_name' => 'Neuroticism',
        ],
        [
            'factor_name' => 'Norms',
        ],
        [
            'factor_name' => 'Overconfidence',
        ],
        [
            'factor_name' => 'Physical fatigue',
        ],
        [
            'factor_name' => 'Recurrence',
        ],
        [
            'factor_name' => 'Risk attitude',
        ],
        [
            'factor_name' => 'Risk-Taking',
        ],
        [
            'factor_name' => 'Security posture',
        ],
        [
            'factor_name' => 'Self-efficacy',
        ],
        [
            'factor_name' => 'Shame',
        ],
        [
            'factor_name' => 'Social Influence',
        ],
        [
            'factor_name' => 'Social Proof',
        ],
        [
            'factor_name' => 'Stress',
        ],
        [
            'factor_name' => 'Support and Organizational Learning',
        ],
        [
            'factor_name' => 'Susceptibility',
        ],
        [
            'factor_name' => 'Type of organization',
        ],
        [
            'factor_name' => 'Vigilance',
        ],
        ]);
    }
}
