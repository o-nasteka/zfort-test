<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreActorRequest;
use App\Models\Actor;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    public function showForm()
    {
        return view('actors.form');
    }

    public function showTable()
    {
        $actors = Actor::orderBy('created_at', 'desc')->get();
        return view('actors.table', compact('actors'));
    }

    public function store(StoreActorRequest $request)
    {
        // Get AI service and extract actor information
        $aiService = app(\App\Services\AI\AIServiceInterface::class);
        
        if (!$aiService->isConfigured()) {
            return redirect()->back()
                ->withErrors(['description' => 'AI service not configured. Please contact administrator.'])
                ->withInput();
        }

        $aiResponse = $aiService->extractActorInfo($request->description);

        if ($aiResponse === null) {
            \Log::error('AI service returned null for description: ' . $request->description);
            return redirect()->back()
                ->withErrors(['description' => 'Failed to process description with AI service. Please try again.'])
                ->withInput();
        }

        \Log::info('AI Response received:', $aiResponse);

        // Validate AI response has required fields
        if (!$this->validateAIResponse($aiResponse)) {
            return redirect()->back()
                ->withErrors(['description' => 'Please add first name, last name, and address to your description.'])
                ->withInput();
        }

        // Create actor record
        $actor = $this->createActor($request, $aiResponse);

        return redirect()->route('actors.table')
            ->with('success', 'Actor information submitted successfully!');
    }

    /**
     * Validate AI response has required fields
     */
    private function validateAIResponse(array $aiResponse): bool
    {
        $requiredFields = ['first_name', 'last_name', 'address'];
        
        foreach ($requiredFields as $field) {
            if (empty($aiResponse[$field])) {
                \Log::error('Missing required field in AI response:', [
                    'field' => $field,
                    'response' => $aiResponse
                ]);
                return false;
            }
        }
        
        return true;
    }

    /**
     * Create actor record from request and AI response
     */
    private function createActor($request, array $aiResponse): Actor
    {
        return Actor::create([
            'email' => $request->email,
            'description' => $request->description,
            'first_name' => $aiResponse['first_name'],
            'last_name' => $aiResponse['last_name'],
            'address' => $aiResponse['address'],
            'height' => $aiResponse['height'] ?? null,
            'weight' => $aiResponse['weight'] ?? null,
            'gender' => $aiResponse['gender'] ?? null,
            'age' => $aiResponse['age'] ?? null,
        ]);
    }

    public function promptValidation()
    {
        $textPrompt = now()->format('Y-m-d H:i:s');
        
        return response()->json([
            'message' => $textPrompt
        ]);
    }
}
