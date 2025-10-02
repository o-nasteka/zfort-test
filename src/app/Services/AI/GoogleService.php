<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class GoogleService implements AIServiceInterface
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('ai.providers.google.api_key', '');
        $this->model = config('ai.providers.google.model', 'gemini-pro');
    }

    /**
     * Extract actor information from description
     */
    public function extractActorInfo(string $description): ?array
    {
        try {
            if (!$this->isConfigured()) {
                Log::error('Google AI API key not configured');
                return null;
            }

            $response = Http::withHeaders([
                'Content-Type' => 'application/json',
            ])->post("https://generativelanguage.googleapis.com/v1beta/models/{$this->model}:generateContent?key={$this->apiKey}", [
                'contents' => [
                    [
                        'parts' => [
                            [
                                'text' => "Extract the following information from the text and return it as JSON: first_name, last_name, address, height, weight, gender, age. If any information is not available, use null for that field.\n\nText: {$description}"
                            ]
                        ]
                    ]
                ],
                'generationConfig' => [
                    'temperature' => 0.1,
                    'maxOutputTokens' => 1000,
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Google AI API request failed: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            $data = $response->json();
            $content = $data['candidates'][0]['content']['parts'][0]['text'] ?? '';
            
            // Try to parse JSON response
            $parsed = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Google AI response: ' . json_last_error_msg());
                return null;
            }

            return $parsed;

        } catch (\Exception $e) {
            Log::error('Google AI API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the service is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'your_google_ai_api_key_here';
    }
}

