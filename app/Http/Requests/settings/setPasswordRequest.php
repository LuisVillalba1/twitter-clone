<?php

namespace App\Http\Requests\settings;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class setPasswordRequest extends FormRequest
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
            "user_password" => ["required",function($attribute,$value,$fail){
                $userData = User::
                select("UserID","Password")
                ->where("UserID",Auth::user()->UserID)
                ->first();

                if(!Hash::check($value,$userData->Password)){
                    $fail("Incorrect password");
                }
            }]
        ];
    }
}
