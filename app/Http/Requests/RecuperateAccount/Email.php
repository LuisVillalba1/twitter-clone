<?php

namespace App\Http\Requests\RecuperateAccount;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class Email extends FormRequest
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
            "mail" =>["email","string",
                function($attribute,$value,$fail){
                    $user = User::where("Email",$value)->first();

                    if(!$user){
                        $fail("Email not found");
                    }
                }
            ]
        ];
    }
}
