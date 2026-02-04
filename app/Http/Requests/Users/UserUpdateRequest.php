<?php

namespace App\Http\Requests\Users;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Hash;

class UserUpdateRequest extends FormRequest
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
            'first_name' => 'sometimes|required|string|max:255',
            'last_name' => 'sometimes|required|string|max:255',
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
}
