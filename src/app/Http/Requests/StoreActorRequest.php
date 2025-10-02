<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreActorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => 'required|email|unique:actors,email',
            'description' => 'required|string|unique:actors,description',
        ];
    }

    /**
     * Get custom messages for validator errors.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'email.required' => 'Email address is required.',
            'email.email' => 'Please enter a valid email address.',
            'email.unique' => 'This email address has already been used.',
            'description.required' => 'Actor description is required.',
            'description.unique' => 'This description has already been submitted.',
        ];
    }
}
