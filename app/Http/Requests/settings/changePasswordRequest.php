<?php

namespace App\Http\Requests\settings;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class changePasswordRequest extends FormRequest
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
            "user_password"=>["required",function($attribute,$value,$fail){
                $password = Auth::user()->Password;

                //en caso de que el usuario no contenga una contraseña enviamos un error
                if(!$password){
                    $fail("Ha ocurrido un error");
                }

                if(!Hash::check($value,$password)){
                    $fail("Contraseña incorrecta");
                }
            }],
            "new_password" =>["required","string","min:4","max:20"],
            "new_password_repeat" =>["required","string","same:new_password"]
        ];
    }
}
