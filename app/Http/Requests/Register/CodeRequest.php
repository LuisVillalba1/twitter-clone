<?php

namespace App\Http\Requests\Register;

use Illuminate\Foundation\Http\FormRequest;

class CodeRequest extends FormRequest
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
            "number1"=>["required","numeric"],
            "number2"=>["required","numeric"],
            "number3"=>["required","numeric"],
            "number4"=>["required","numeric"],
            "number5"=>["required","numeric"],
        ];
    }
}
