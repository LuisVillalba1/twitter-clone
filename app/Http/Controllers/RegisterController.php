<?php

namespace App\Http\Controllers;

use App\Http\Requests\Register\CodeRequest;
use App\Http\Requests\Register\NickAndPasswordRequest;
use App\Http\Requests\Register\NicknameRequest;
use App\Http\Requests\Register\Step1Register;
use App\Models\User;
use App\Models\VerificationAccount;
use Illuminate\Contracts\Session\Session;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    //show main register
    public function main(){
        return view("create-account.personalData");
    }

    //personal data register post
    public function createStep1(Step1Register $request){
        return (new User())->safePersonalDate($request);
    }

    //show create username register
    public function showStep2(){
        return view("create-account.accountData");
    }

    //create username
    public function createStep2(NickAndPasswordRequest $request){
        return (new User())->safeAndSendEmail($request);
    }

    //show code email register
    public function showStep3(){
        return view("create-account.codeEmail");
    }

    public function createStep3(CodeRequest $request){
        $suma = [];
        // iteramos sobre los inputs
        for($i = 1;$i <=5;$i++){
            $valor = $request->input("number{$i}");
            array_push($suma,$valor);
        }

        $code = implode("",$suma);

        return (new VerificationAccount())->checkCode($code);
    }

    public function showStep4(){
        return view("create-account.passwordAccount");
    }
}
