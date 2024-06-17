<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterUserRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'confirmed', 'min:6'],
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
            'name.required' => 'Please enter your name.',
            'name.string' => 'Your name must be text.',
            'name.max' => 'Your name cannot be more than 255 characters.',
            'email.required' => 'Please enter your email address.',
            'email.string' => 'Your email address must be a valid email format.',
            'email.email' => 'Please enter a valid email address.',
            'email.max' => 'Your email address cannot be more than 255 characters.',
            'email.unique' => 'The email address you entered is already registered.',
            'password.required' => 'Please enter a password.',
            'password.confirmed' => 'The password confirmation does not match the password.',
            'password.min' => 'Your password must be at least 6 characters long.',
        ];
    }
}
