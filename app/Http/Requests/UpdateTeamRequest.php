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
                'manager_id' => 'required|exists:users,id',
                'user_ids' => 'array|distinct', // Ensure at least one unique user ID
                'user_ids.*' => 'exists:users,id', // Validate each user ID exists
            ];
        }else {
            return 
            [
                'name' => 'sometimes|required|string|max:255',
                'manager_id' => 'sometimes|required|exists:users,id',
                'user_ids' => 'sometimes|array|distinct', // Ensure at least one unique user ID
                'user_ids.*' => 'sometimes|exists:users,id', // Validate each user ID exists
            ];
        }
    }
}
