<?php

namespace App\Http\Requests\InitSession;

use App\Models\PersonalData;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class InitSessionRequest extends FormRequest
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
            "user"=>["required","string",
                function($attribute,$value,$fail){
                    $email = User::where("Email",$value)->first();

                    $nickName = PersonalData::where("Nickname",$value)->first();

                    if(!$email && !$nickName){
                        $fail("Email or Nickname not found");
                    }
                }
            ],
            "password"=>["required","string"]
        ];
    }
}
