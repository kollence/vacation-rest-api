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
        if($method == 'PUT'){
            return
            [
                'name' => 'required|string|max:255',
                'managers' => 'required|array|min:1',
                'managers.*' => 'exists:users,id',
                'regular_users' => 'array|distinct', // Ensure at least one unique user ID
                'regular_users.*' => 'exists:users,id', // Validate each user ID exists
            ];
        }else {
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
}
