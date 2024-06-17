<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateTeamRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'managers' => 'required|array',
            'managers.*' => 'required|integer|exists:users,id',
            'regular_users' => 'nullable|array',
            'regular_users.*' => 'nullable|integer|exists:users,id|unique:team_user,user_id,NULL,team_id',
        ];
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
            'managers.*.required' => 'Each manager ID is required.',
            'managers.*.integer' => 'Each manager ID must be an integer.',
            'managers.*.exists' => 'The manager ID must exist in the users table.',
            'regular_users.nullable' => 'The regular users field is optional.',
            'regular_users.array' => 'The regular users field must be an array (if provided).',
            'regular_users.*.nullable' => 'Each regular user ID is optional (if provided).',
            'regular_users.*.integer' => 'Each regular user ID must be an integer (if provided).',
            'regular_users.*.exists' => 'The regular user ID must exist in the users table (if provided).',
            'regular_users.*.unique' => 'A user cannot be a member of multiple teams (except as a regular user).',
        ];
    }
}
