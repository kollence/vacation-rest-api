<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateVacationRequest extends FormRequest
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
                    'start_date' => 'required|date|after:today',
                    'end_date' => 'required|date|after_or_equal:start_date',
                    'reason' => 'nullable|string|max:255',
                ];
        } else {
            return
                [
                    'start_date' => 'sometimes|required|date|after:today',
                    'end_date' => 'sometimes|required|date|after_or_equal:start_date',
                    'reason' => 'sometimes|nullable|string|max:255',
                ];
        }
    }
}
