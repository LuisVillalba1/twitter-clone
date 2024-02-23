<?php

namespace App\Http\Requests\settings;

use Illuminate\Foundation\Http\FormRequest;

class EditProfileRequest extends FormRequest
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
            "coverPhoto"=>["image","mimes:jpeg"],
            "profilePhoto"=>["image","mimes:jpeg,jpg,png"],
            "name"=>["string","min:3","max:15"],
            "bio"=>["nullable","string","max:200"]
        ];
    }
}
