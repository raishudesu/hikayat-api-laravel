<?php

namespace App\Http\Requests\Posts;

use Illuminate\Foundation\Http\FormRequest;

class CreatePostRequest extends FormRequest
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
            'user_id' => "required|int",
            "parent_id" => "sometimes|int",
            "title" => "string|max:256",
            "content" => "text|max:1000",
            "latitude" => "sometimes|float",
            "longitude" => "sometimes|float"
        ];
    }
}
