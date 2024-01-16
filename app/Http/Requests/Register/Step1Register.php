<?php

namespace App\Http\Requests\Register;

use App\Models\User;
use App\Rules\SpaceBeginAndEnd;
use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Date;

class Step1Register extends FormRequest
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
            "name" =>["required","string","max:30","min:1"],
            "email" =>["required","email",
                function($attribute,$value,$fail){
                    $email = User::where("Email",$value)->first();
                    if($email){
                        $fail("The email is already in use");
                    }
                }
            ],
            "date" =>["required","date","before:today",
                function($attribute,$value,$fail){
                    $today = Carbon::now();

                    $fechaLimite = $today->copy()->subYears(120);

                    if($value < $fechaLimite){
                        $fail("Date cant be less than 120 years ago");
                    }
                }
            ],
        ];
    }
}
