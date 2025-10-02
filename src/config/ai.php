<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default AI Provider
    |--------------------------------------------------------------------------
    |
    | This option controls the default AI provider that will be used
    | to extract actor information from descriptions.
    |
    | Supported: "openai", "anthropic", "google"
    |
    */
    'default_provider' => env('AI_DEFAULT_PROVIDER', 'openai'),

    /*
    |--------------------------------------------------------------------------
    | AI Providers Configuration
    |--------------------------------------------------------------------------
    |
    | Here you may configure the settings for each AI provider.
    |
    */
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => env('OPENAI_MODEL', 'gpt-3.5-turbo'),
            'base_url' => env('OPENAI_BASE_URL', 'https://api.openai.com/v1'),
        ],
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => env('ANTHROPIC_MODEL', 'claude-3-sonnet-20240229'),
        ],
        'google' => [
            'api_key' => env('GOOGLE_AI_API_KEY'),
            'model' => env('GOOGLE_AI_MODEL', 'gemini-pro'),
        ],
    ],
];
