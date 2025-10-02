<?php

namespace Tests\Feature;

use App\Services\AI\AIServiceInterface;
use App\Services\AI\OpenAIService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AIServiceTest extends TestCase
{
    use RefreshDatabase;

    public function test_ai_service_can_be_resolved_from_container(): void
    {
        $aiService = $this->app->make(AIServiceInterface::class);
        
        $this->assertInstanceOf(OpenAIService::class, $aiService);
    }

    public function test_ai_service_implements_interface(): void
    {
        $aiService = $this->app->make(AIServiceInterface::class);
        
        $this->assertTrue(method_exists($aiService, 'extractActorInfo'));
        $this->assertTrue(method_exists($aiService, 'isConfigured'));
    }

    public function test_openai_service_is_configured_with_valid_key(): void
    {
        config(['ai.providers.openai.api_key' => 'sk-test-key']);
        
        $service = new OpenAIService();
        
        $this->assertTrue($service->isConfigured());
    }

    public function test_openai_service_is_not_configured_with_placeholder(): void
    {
        config(['ai.providers.openai.api_key' => 'your_openai_api_key_here']);
        
        $service = new OpenAIService();
        
        $this->assertFalse($service->isConfigured());
    }
}
