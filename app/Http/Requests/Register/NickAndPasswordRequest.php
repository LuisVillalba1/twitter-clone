<?php

namespace App\Http\Requests\Register;

use App\Models\PersonalData;
use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;

class NickAndPasswordRequest extends FormRequest
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
            "nickname" =>["required","string","min:3","max:20",
                function($attibute,$value,$fail){
                    $nickname = PersonalData::where("Nickname",$value)->first();

                    if($nickname){
                        $fail("The nickname is already exist");
                    }
                }
            ],
            "password"=>["required","string","min:4","max:20"]
        ];
    }
}
