# Architecture Documentation

## SOLID Principles Implementation

This application follows SOLID principles and implements a clean architecture pattern for AI service integration.

### 1. Single Responsibility Principle (SRP)
- **AIServiceInterface**: Defines the contract for AI services
- **OpenAIService**: Handles only OpenAI API interactions
- **AnthropicService**: Handles only Anthropic API interactions
- **GoogleService**: Handles only Google AI API interactions
- **AIServiceProvider**: Handles only service registration

### 2. Open/Closed Principle (OCP)
- The system is open for extension (new AI providers) but closed for modification
- New AI providers can be added by implementing `AIServiceInterface`
- No need to modify existing code when adding new providers

### 3. Liskov Substitution Principle (LSP)
- Any implementation of `AIServiceInterface` can be substituted without breaking functionality
- All AI services follow the same contract

### 4. Interface Segregation Principle (ISP)
- `AIServiceInterface` contains only methods that clients need
- No unnecessary dependencies or methods

### 5. Dependency Inversion Principle (DIP)
- High-level modules (validation rules) depend on abstractions (`AIServiceInterface`)
- Low-level modules (specific AI services) implement the abstractions
- Dependency injection is used throughout

## Architecture Patterns

### Service Provider Pattern
```php
// AIServiceProvider registers the appropriate AI service
$this->app->bind(AIServiceInterface::class, function ($app) {
    $provider = config('ai.default_provider', 'openai');
    
    return match ($provider) {
        'openai' => new OpenAIService(),
        'anthropic' => new AnthropicService(),
        'google' => new GoogleService(),
        default => throw new \InvalidArgumentException("Unsupported AI provider: {$provider}")
    };
});
```

### Strategy Pattern
- Different AI providers implement the same interface
- The appropriate strategy is selected at runtime based on configuration

### Factory Pattern
- Service provider acts as a factory for creating AI service instances

## Configuration

### Environment Variables
```env
# AI Configuration
AI_DEFAULT_PROVIDER=openai

# OpenAI Configuration
OPENAI_API_KEY=your_openai_api_key
OPENAI_MODEL=gpt-3.5-turbo

# Anthropic Configuration
ANTHROPIC_API_KEY=your_anthropic_api_key
ANTHROPIC_MODEL=claude-3-sonnet-20240229

# Google AI Configuration
GOOGLE_AI_API_KEY=your_google_ai_api_key
GOOGLE_AI_MODEL=gemini-pro
```

### Configuration Files
- `config/ai.php`: AI service configuration
- `config/services.php`: Third-party service credentials

## Adding New AI Providers

1. **Create the service class**:
```php
class NewAIService implements AIServiceInterface
{
    public function extractActorInfo(string $description): ?array
    {
        // Implementation
    }
    
    public function isConfigured(): bool
    {
        // Configuration check
    }
}
```

2. **Update the service provider**:
```php
return match ($provider) {
    'openai' => new OpenAIService(),
    'anthropic' => new AnthropicService(),
    'google' => new GoogleService(),
    'newai' => new NewAIService(), // Add new provider
    default => throw new \InvalidArgumentException("Unsupported AI provider: {$provider}")
};
```

3. **Add configuration**:
```php
// config/ai.php
'providers' => [
    'openai' => [
        'api_key' => env('OPENAI_API_KEY'),
        'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
    ],
    'anthropic' => [
        'api_key' => env('ANTHROPIC_API_KEY'),
        'model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
    ],
    'google' => [
        'api_key' => env('GOOGLE_AI_API_KEY'),
        'model' => env('GOOGLE_AI_MODEL', 'gemini-pro'),
    ],
    'newai' => [
        'api_key' => env('NEWAI_API_KEY'),
        'model' => env('NEWAI_MODEL', 'default-model'),
    ],
],
```

## Benefits

### Maintainability
- Clear separation of concerns
- Easy to modify individual components
- Well-defined interfaces

### Testability
- Services can be easily mocked
- Dependency injection enables testing
- Each component can be tested in isolation

### Extensibility
- New AI providers can be added without modifying existing code
- Configuration-driven provider selection
- Easy to switch between providers

### Flexibility
- Can switch AI providers by changing configuration
- Supports multiple AI providers simultaneously
- Easy to add new features to existing providers

## Testing

The architecture supports comprehensive testing:

```php
// Test service resolution
$aiService = $this->app->make(AIServiceInterface::class);

// Test configuration
$service = new OpenAIService();
$this->assertTrue($service->isConfigured());

// Test interface implementation
$this->assertTrue(method_exists($aiService, 'extractActorInfo'));
```

## Usage Example

```php
// In service provider registration
$this->app->bind(AIServiceInterface::class, function ($app) {
    $provider = config('ai.default_provider', 'openai');
    
    return match ($provider) {
        'openai' => new OpenAIService(),
        'anthropic' => new AnthropicService(),
        'google' => new GoogleService(),
        default => throw new \InvalidArgumentException("Unsupported AI provider: {$provider}")
    };
});

// In controller or service
public function __construct(AIServiceInterface $aiService)
{
    $this->aiService = $aiService;
}

// Extract actor information
$actorInfo = $this->aiService->extractActorInfo($description);
```

This architecture ensures the application is maintainable, testable, and easily extensible while following industry best practices.
