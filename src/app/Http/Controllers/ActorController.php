<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;

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

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email|unique:actors,email',
            'description' => 'required|string|unique:actors,description',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Call OpenAI API to extract information
            $openaiResponse = $this->callOpenAI($request->description);
            
            if (!$openaiResponse) {
                return redirect()->back()
                    ->withErrors(['description' => 'Failed to process description with OpenAI API'])
                    ->withInput();
            }

            // Check if required fields are present
            if (empty($openaiResponse['first_name']) || 
                empty($openaiResponse['last_name']) || 
                empty($openaiResponse['address'])) {
                return redirect()->back()
                    ->withErrors(['description' => 'Please add first name, last name, and address to your description.'])
                    ->withInput();
            }

            // Create actor record
            $actor = Actor::create([
                'email' => $request->email,
                'description' => $request->description,
                'first_name' => $openaiResponse['first_name'],
                'last_name' => $openaiResponse['last_name'],
                'address' => $openaiResponse['address'],
                'height' => $openaiResponse['height'] ?? null,
                'weight' => $openaiResponse['weight'] ?? null,
                'gender' => $openaiResponse['gender'] ?? null,
                'age' => $openaiResponse['age'] ?? null,
            ]);

            return redirect()->route('actors.table')
                ->with('success', 'Actor information submitted successfully!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->withErrors(['description' => 'An error occurred while processing your request.'])
                ->withInput();
        }
    }

    private function callOpenAI($description)
    {
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type' => 'application/json',
            ])->post('https://api.openai.com/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    [
                        'role' => 'system',
                        'content' => 'Extract the following information from the text and return it as JSON: first_name, last_name, address, height, weight, gender, age. If any information is not available, use null for that field.'
                    ],
                    [
                        'role' => 'user',
                        'content' => $description
                    ]
                ],
                'temperature' => 0.1
            ]);

            if ($response->successful()) {
                $data = $response->json();
                $content = $data['choices'][0]['message']['content'] ?? '';
                
                // Try to parse JSON response
                $parsed = json_decode($content, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $parsed;
                }
            }

            return null;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function promptValidation()
    {
        $textPrompt = now()->format('Y-m-d H:i:s');
        
        return response()->json([
            'message' => $textPrompt
        ]);
    }
}
