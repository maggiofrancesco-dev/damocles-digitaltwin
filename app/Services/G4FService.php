<?php
namespace App\Services;

use App\Models\LLM;
use Exception;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class G4FService{
    public static function generateHTTPPost(LLM $llm, string $prompt)
    {
        $endpoint = $llm->endpoint;
        $provider = $llm->provider;
        $model = $llm->model;

        $data = [
            'provider' => $provider,
            'model' => $model,
            'messages' => [
                [
                    'role' => 'user',
                    'content' => $prompt,
                ]
            ]
        ];

        $response = Http::post($endpoint, $data);

        if ($response->successful()) {
            $responseData = $response->json();

            $choices = $responseData['choices'];
            $content = $choices[0]['message']['content'];

            // Remove any '#' or '*' characters from the content
            // $content = preg_replace('/[#*]/', '', $content);

            return $content;
        } else {
            throw new Exception('Request failed');
        }
    }
}