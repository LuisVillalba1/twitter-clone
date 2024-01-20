<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecuperateAccount\Email;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class RecuperateAccountController extends Controller
{
    //show view recuperateAccount
    public function recuperateAccount(){
        return view("recuperateAccount.emailAccount");
    }

    //send email to recuperateAccount
    public function sendEmail(Email $request){
        return (new User())->recuperateAccount($request);
    }

    //show view recuperateAccount
    public function changePassword(Request $request,$id){
        if($request->hasValidSignature()){
            $link = URL::temporarySignedRoute(
                "changePasswordPost",
                now()->addHours(1),
                ["id"=>$id]
            );

            return view("recuperateAccount.changePassword",compact("link"));
        }
        return redirect()->route("main");
    }
}
