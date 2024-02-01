<?php

namespace App\Http\Controllers;

use App\Http\Requests\RecuperateAccount\Email;
use App\Http\Requests\RecuperateAccount\NewPasswordRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;

class RecuperateAccountController extends Controller
{
    //mostramos la vista para recuperar la cuenta
    public function recuperateAccount(){
        return view("recuperateAccount.emailAccount");
    }

    //enviamos un mail para recuperar la cuenta 
    public function sendEmail(Email $request){
        return (new User())->recuperateAccount($request);
    }

    //mostramos la vista para recupear la cuenta
    public function changePassword(Request $request,$id){
        //en caso de que el link no haya expirado mostramos la vista para modificar la contraseña
        if($request->hasValidSignature()){
            $link = URL::temporarySignedRoute(
                "changePasswordPatch",
                now()->addHours(1),
                ["id"=>$id]
            );

            return view("recuperateAccount.changePassword",compact("link"));
        }
        session()->put("error","Ruta expirada o invalida");
        return redirect()->route("errorPage");
    }

    //cambiamos la contraseña del usuario
    public function change(NewPasswordRequest $request,$id){
        return (new User())->changePassword($request,$id);
    }
}
