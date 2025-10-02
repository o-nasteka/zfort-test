<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class OpenAIService implements AIServiceInterface
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('ai.providers.openai.api_key', '');
        $this->model = config('ai.providers.openai.model', 'gpt-3.5-turbo');
    }

    /**
     * Extract actor information from description
     */
    public function extractActorInfo(string $description): ?array
    {
        try {
            if (!$this->isConfigured()) {
                Log::error('OpenAI API key not configured');
                return null;
            }

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => $this->model,
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => config('ai.prompts.extract_actor_info')
                    ],
                    [
                        'role' => 'user',
                        'content' => $description
                    ]
                ],
                'temperature' => 0.1
            ]);

            if (!$response->successful()) {
                Log::error('OpenAI API request failed: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            $data = $response->json();
            $content = $data['choices'][0]['message']['content'] ?? '';
            
            // Try to parse JSON response
            $parsed = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse OpenAI response: ' . json_last_error_msg());
                return null;
            }

            return $parsed;

        } catch (\Exception $e) {
            Log::error('OpenAI API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the service is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'your_openai_api_key_here';
    }
}
