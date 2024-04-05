<?php

namespace App\Http\Requests\settings\PersonalData;

use Carbon\Carbon;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Auth;

class BirthdayRequest extends FormRequest
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

    //  "before:today"
    public function rules(): array
    {
        return [
            "date" =>["required","date",
            function($attribute,$value,$fail){
                //obtenemos la fecha en la que se ha creado la cuenta
                $created_account = Auth::user()->created_at;

                //no permitimos que esta pueda ingresar una edad inferior a cuando se creo la cuenta
                if($value > $created_account){
                    $fail("Date cant be less than when you have created the account");
                }

                $fechaLimite = $created_account->copy()->subYears(120);

                if($value < $fechaLimite){
                    $fail("Date cannot be less than 120 years from when you created your account");
                }
            }
        ],
        ];
    }
}
