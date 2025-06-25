<?php
namespace App\Services;

class JsonExtractor
{
    public static function extract(string $body): array
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
}
