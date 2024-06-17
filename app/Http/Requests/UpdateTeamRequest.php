<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateTeamRequest extends FormRequest
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
        //CURRENT METHOD
        $method = $this->method();
        if ($method == 'PUT') {
            return
                [
                    'name' => 'required|string|max:255',
                    'managers' => 'required|array|min:1',
                    'managers.*' => 'exists:users,id',
                    'regular_users' => 'array|distinct', // Ensure at least one unique user ID
                    'regular_users.*' => 'exists:users,id', // Validate each user ID exists
                ];
        } else {
            return
                [
                    'name' => 'sometimes|required|string|max:255',
                    'managers' => 'sometimes|required|array|min:1',
                    'managers.*' => 'sometimes|exists:users,id',
                    'regular_users' => 'sometimes|array|distinct', // Ensure at least one unique user ID
                    'regular_users.*' => 'sometimes|exists:users,id', // Validate each user ID exists
                ];
        }
    }

    /**
     * Get custom validation messages for attributes.
     *
     * @return array<string, mixed>
     */
    public function messages()
    {
        return [
            'name.required' => 'The team name is required.',
            'name.string' => 'The team name must be a string.',
            'name.max' => 'The team name must be no more than 255 characters.',
            'managers.required' => 'The team must have at least one manager.',
            'managers.array' => 'The managers field must be an array.',
            'managers.min' => 'The team must have at least one manager.', // Adjusted for clarity
            'managers.*.exists' => 'A manager ID provided is not found in the users table.', // More specific message
            'regular_users.array' => 'The regular users field must be an array (if provided).',
            'regular_users.distinct' => 'The regular users list must contain unique user IDs.', // Explain the distinct validation
            'regular_users.*.exists' => 'A regular user ID provided is not found in the users table.', // More specific message

            // Messages with "sometimes" prefix for PATCH requests (optional updates)
            'sometimes.name.required' => 'The team name is required if updating.',
            'sometimes.managers.required' => 'The team must have at least one manager if updating.',
            'sometimes.managers.array' => 'The managers field must be an array if updating.',
            'sometimes.managers.min' => 'The team must have at least one manager if updating.', // Adjusted for clarity
            'sometimes.managers.*.exists' => 'A manager ID provided is not found in the users table.', // More specific message
            'sometimes.regular_users.array' => 'The regular users field must be an array (if provided when updating).',
            'sometimes.regular_users.distinct' => 'The regular users list must contain unique user IDs if updating.', // Explain the distinct validation
            'sometimes.regular_users.*.exists' => 'A regular user ID provided is not found in the users table if updating.', // More specific message
        ];
    }
}
