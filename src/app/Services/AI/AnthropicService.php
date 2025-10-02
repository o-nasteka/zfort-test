<?php

namespace App\Services\AI;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AnthropicService implements AIServiceInterface
{
    private string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('ai.providers.anthropic.api_key', '');
        $this->model = config('ai.providers.anthropic.model', 'claude-3-sonnet-20240229');
    }

    /**
     * Extract actor information from description
     */
    public function extractActorInfo(string $description): ?array
    {
        try {
            if (!$this->isConfigured()) {
                Log::error('Anthropic API key not configured');
                return null;
            }

            $response = Http::withHeaders([
                'x-api-key' => $this->apiKey,
                'Content-Type' => 'application/json',
                'anthropic-version' => '2023-06-01',
            ])->post('https://api.anthropic.com/v1/messages', [
                'model' => $this->model,
                'max_tokens' => 1000,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => "Extract the following information from the text and return it as JSON: first_name, last_name, address, height, weight, gender, age. If any information is not available, use null for that field.\n\nText: {$description}"
                    ]
                ]
            ]);

            if (!$response->successful()) {
                Log::error('Anthropic API request failed: ' . $response->status() . ' - ' . $response->body());
                return null;
            }

            $data = $response->json();
            $content = $data['content'][0]['text'] ?? '';
            
            // Try to parse JSON response
            $parsed = json_decode($content, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                Log::error('Failed to parse Anthropic response: ' . json_last_error_msg());
                return null;
            }

            return $parsed;

        } catch (\Exception $e) {
            Log::error('Anthropic API exception: ' . $e->getMessage());
            return null;
        }
    }

    /**
     * Check if the service is properly configured
     */
    public function isConfigured(): bool
    {
        return !empty($this->apiKey) && $this->apiKey !== 'your_anthropic_api_key_here';
    }
}
