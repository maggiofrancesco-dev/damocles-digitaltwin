<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
/**
 * Author: Gioele Giannico
 */
class DigitalTwinsPromptSeeder extends Seeder
{
    public function run(): void
    {
        $prompts = [
            [
                'title' => 'Cognitive fatigue (similar to decision fatigue)',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person has been identified as particularly vulnerable due to 'Cognitive fatigue (similar to decision fatigue)'. This means they are likely to make poor cybersecurity decisions when exposed to stressors or persuasive communication linked to this factor.

The LLM should simulate this individual's behavior in a phishing scenario. Incorporate the implications of 'Cognitive fatigue (similar to decision fatigue)' into the decision-making reasoning of the digital twin, including:
- their response to urgent or official-looking emails,
- how their cognitive/emotional bias might lead to a click,
- and their likelihood to ignore or report a suspicious message.

Make sure the digital twin emulates a consistent personality profile aligned with 'Cognitive fatigue (similar to decision fatigue)'.
",
            ],
            [
                'title' => 'Uncertainty',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person has been identified as particularly vulnerable due to 'Uncertainty'. This means they are likely to second-guess their instincts, hesitate in decision-making, and potentially follow misleading directions from authoritative-looking emails.

The LLM should simulate this individual's behavior in a phishing scenario. Incorporate the implications of 'Uncertainty' into the reasoning of the digital twin, especially their indecisiveness and reliance on perceived guidance.
",
            ],
            [
                'title' => 'Recurrence',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person tends to repeat past behaviors and decisions due to 'Recurrence'. When exposed to a phishing message similar to one they've trusted before, they are highly likely to repeat the same mistake.

The LLM must simulate how familiarity and habit influence this digital twin's decision-making in email interactions.
",
            ],
            [
                'title' => 'Anxiousness',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This individual experiences heightened anxiety in high-pressure contexts. Their anxiousness can make them more reactive to urgency-based phishing, often acting without due caution.

The LLM should simulate how emotional discomfort influences their decision to engage with suspicious messages.
",
            ],
            [
                'title' => 'Calmness',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person is generally calm and measured in their reactions. While typically a strength, it can lead to complacency or slower response in detecting suspicious patterns.

The LLM must emulate how 'Calmness' influences their cybersecurity reactions, particularly when the risk isn't immediately obvious.
",
            ],
            [
                'title' => 'Lack of Resources',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person operates in a resource-constrained environment, which may reduce their ability to verify suspicious communications.

The LLM should simulate how 'Lack of Resources' influences their choices, shortcuts taken, and reaction to urgent phishing messages.
",
            ],
            [
                'title' => 'Risk-Taking',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person has a high tolerance for risk and often acts without full evaluation of consequences. They may disregard subtle red flags in communications.

The LLM should emulate this risk-prone decision-making pattern in phishing simulations.
",
            ],
            [
                'title' => 'Extroversion',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This individual's extroversion leads them to engage frequently with people and communication. They may be more inclined to click or respond to unexpected outreach.

Simulate their tendency to trust or respond to social interactions in email-based phishing.
",
            ],
            [
                'title' => 'Distraction',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person often multitasks and struggles with attention. Phishing that relies on misdirection or urgency is more likely to succeed.

The LLM should simulate lapses in scrutiny and impulse clicking.
",
            ],
            [
                'title' => 'Overconfidence',
                'value' => "Create a digital twin of a person with the following characteristics:
- Name: {{name}} {{surname}}
- Age: {{age}}
- Role: {{role}} at a mid-sized organization
- Gender: {{gender}}

This person believes they are immune to cyber threats and often skips verification steps. Their overconfidence may lead them to fall for advanced phishing.

The LLM must model a persona that trusts their instincts excessively, even when they are misguided.
",
            ]
        ];

        foreach ($prompts as $prompt) {
            DB::table('digital_twins_prompt')->insert([
                'title' => $prompt['title'],
                'value' => $prompt['value'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
