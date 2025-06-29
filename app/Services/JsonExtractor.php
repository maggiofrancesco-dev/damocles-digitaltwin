<?php
namespace App\Services;

use Illuminate\Support\Facades\Log;

class JsonExtractor
{
    public static function extractEmail(string $body): array
    {
        if (preg_match('/\{[\s\S]*\}/', $body, $matches)) {
            $jsonString = $matches[0];

            $data = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($data['subject'], $data['body'])) {
                    return [
                        'subject' => $data['subject'],
                        'body' => $data['body'],
                    ];
                } else {
                    throw new \InvalidArgumentException('JSON does not contain required "subject" and "body" keys.');
                }
            } else {
                throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
            }
        } else {
            throw new \InvalidArgumentException('No JSON block found in input text.');
        }
    }

    public static function extractDigitalTwinAttack(string $body): array
    {
        if (preg_match('/\{[\s\S]*\}/', $body, $matches)) {
            $jsonString = $matches[0];

            $data = json_decode($jsonString, true);

            if (json_last_error() === JSON_ERROR_NONE) {
                if (isset($data['internal_reasoning'], $data['sequence_of_actions'], $data['outcome'], $data['post_actions_emotions'],$data['clicked'],$data['opened'] )) {
                    return [
                        'internal_reasoning' => $data['internal_reasoning'],
                        'sequence_of_actions' => $data['sequence_of_actions'],
                        'outcome' => $data['outcome'],
                        'post_actions_emotions' => $data['post_actions_emotions'],
                        'clicked' => $data['clicked'],
                        'opened' => $data['opened'],
                    ];
                } else {
                    throw new \InvalidArgumentException('JSON does not contain required the required keys.');
                }
            } else {
                throw new \InvalidArgumentException('Invalid JSON: ' . json_last_error_msg());
            }
        } else {
            throw new \InvalidArgumentException('No JSON block found in input text.');
        }
    }
}
