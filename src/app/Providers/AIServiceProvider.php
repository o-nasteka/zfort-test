<?php

namespace App\Providers;

use App\Services\AI\AIServiceInterface;
use App\Services\AI\AnthropicService;
use App\Services\AI\GoogleService;
use App\Services\AI\OpenAIService;
use Illuminate\Support\ServiceProvider;

class AIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->bind(AIServiceInterface::class, function ($app) {
            $provider = config('ai.default_provider', 'openai');
            
            return match ($provider) {
                'openai' => new OpenAIService(),
                'anthropic' => new AnthropicService(),
                'google' => new GoogleService(),
                default => throw new \InvalidArgumentException("Unsupported AI provider: {$provider}")
            };
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        //
    }
}
