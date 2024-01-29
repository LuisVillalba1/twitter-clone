<?php

namespace App\Http\Requests\UserPost;

use Illuminate\Foundation\Http\FormRequest;

class NewPostRequest extends FormRequest
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
            "images.*"=>["image","mimes:jpeg,jpg,png,gif"],
            "images.*.name"=>["string"],
            "images.*.size"=>["max:5120"],
            "message" =>["max:280"],
            //al menos uno de los dos campos debe de existir
            'images' => 'required_without_all:message',
            'message' => 'required_without_all:images',
        ];
    }
}
