<?php

namespace App\Http\Requests\Register\Google;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\PersonalData;

class NicknameRequest extends FormRequest
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
            "nickname" => ["required","string","min:3","max:20",
            function($attibute,$value,$fail){
                $nickname = PersonalData::where("Nickname",$value)->first();

                if($nickname){
                    $fail("The nickname is already exist");
                }
            }
            ]
        ];
    }
}
