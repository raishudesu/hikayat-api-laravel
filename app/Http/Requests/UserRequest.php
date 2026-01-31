<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
        if ($this->isMethod('get')) {
            return [
                'page' => 'nullable|integer|min:1',
                'per_page' => 'nullable|integer|min:1|max:100',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                'email' => 'sometimes|required|email|unique:users,email,' . $this->route('user') . ',uuid',
                'bio' => 'nullable|string',
                'settings' => 'nullable|array',
                'social_links' => 'nullable|array',
                'is_verified_guide' => 'sometimes|boolean',
                'password' => 'sometimes|required|string|min:8',
            ];
        }

        return [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'id' => 'string|uuid',
        ];
    }
}
