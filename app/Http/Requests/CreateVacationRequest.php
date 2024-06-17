<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CreateVacationRequest extends FormRequest
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
            'start_date' => 'required|date|after:today',
            'end_date' => 'required|date|after_or_equal:start_date',
            'reason' => 'nullable|string|max:255',
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
            'start_date.required' => 'The start date for your vacation request is required.',
            'start_date.date' => 'The start date must be a valid date format (YYYY-MM-DD).',
            'start_date.after' => 'The start date of your vacation cannot be in the past. Please choose a date after today.',
            'end_date.required' => 'The end date for your vacation request is required.',
            'end_date.date' => 'The end date must be a valid date format (YYYY-MM-DD).',
            'end_date.after_or_equal' => 'The end date of your vacation must be after or equal to the start date.',
            'reason.nullable' => 'The reason for your vacation request is optional.',
            'reason.string' => 'The reason for your vacation request must be text.',
            'reason.max' => 'The reason for your vacation request cannot be more than 255 characters.',
        ];
    }
}
