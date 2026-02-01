<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

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
                'username' => 'nullable|string|max:255',
            ];
        }

        if ($this->isMethod('put') || $this->isMethod('patch')) {
            return [
                'first_name' => 'sometimes|required|string|max:255',
                'last_name' => 'sometimes|required|string|max:255',
                // 'email' => 'sometimes|required|email|unique:users,email,' . ($this->route('user') instanceof User ? $this->route('user')->uuid : $this->route('user')) . ',uuid',
                'username' => 'sometimes|required|string|max:255|unique:users,username,' . ($this->route('user') instanceof User ? $this->route('user')->uuid : $this->user()->uuid) . ',uuid',
                'bio' => 'nullable|string',
                'settings' => 'nullable|array',
                'social_links' => 'nullable|array',
                'is_verified_guide' => 'sometimes|boolean',
                'password' => [
                    'sometimes',
                    'required',
                    'string',
                    'min:8',
                    function ($attribute, $value, $fail) {
                        $user = $this->route('user') ?? $this->user();

                        // If it's not a User instance yet (e.g. binding failed or not type-hinted), skip
                        if (!$user instanceof User) {
                            return;
                        }

                        if (Hash::check($value, $user->password)) {
                            $fail('You cannot use your old password.');
                        }
                    },
                ],
            ];
        }

        return [
            'page' => 'nullable|integer|min:1',
            'per_page' => 'nullable|integer|min:1|max:100',
            'id' => 'string|uuid',
        ];
    }
}
