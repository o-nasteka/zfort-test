<?php

namespace Tests\Feature;

use App\Models\Actor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActorControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_form_page_loads(): void
    {
        $response = $this->get('/actors/form');

        $response->assertStatus(200);
        $response->assertViewIs('actors.form');
    }

    public function test_table_page_loads(): void
    {
        $response = $this->get('/actors/table');

        $response->assertStatus(200);
        $response->assertViewIs('actors.table');
    }

    public function test_api_prompt_validation_endpoint(): void
    {
        $response = $this->get('/api/actors/prompt-validation');

        $response->assertStatus(200);
        $response->assertJsonStructure(['message']);
    }

    public function test_form_validation_requires_email(): void
    {
        $response = $this->post('/actors', [
            'description' => 'John Doe, 123 Main St, Male, 30 years old'
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_form_validation_requires_description(): void
    {
        $response = $this->post('/actors', [
            'email' => 'test@example.com'
        ]);

        $response->assertSessionHasErrors(['description']);
    }

    public function test_form_validation_requires_valid_email(): void
    {
        $response = $this->post('/actors', [
            'email' => 'invalid-email',
            'description' => 'John Doe, 123 Main St, Male, 30 years old'
        ]);

        $response->assertSessionHasErrors(['email']);
    }
}
