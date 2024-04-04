<?php

namespace App\Http\Controllers;

use App\Http\Requests\settings\changePasswordRequest;
use App\Http\Requests\settings\setPasswordRequest;
use App\Models\User;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class SettingsController extends Controller
{   
    //mostramos la de configuracion de cuenta
    public function settings(){
        try{
            $user = Auth::user();
            $username = $user->PersonalData->Nickname;
    
            return view("app.settings.account.accountSetting",compact("username"));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //mostramos la informacion del usuario,como el mail,nombre de usuario,nombre, y fecha de nacimiento
    public function accountInformation(Request $request){
        try{
            $user = User::
            select("UserID","PersonalDataID","Email","Name","Password")
            ->with([
                "PersonalData"=>function ($queryPersonal){
                    $queryPersonal->select("PersonalDataID","Nickname","Date");
                }
            ])
            ->where("UserID",Auth::user()->UserID)
            ->first();
    
            $username = $user->PersonalData->Nickname;
            //en caso de que aun no se haya ingresado la contraseña,y este posea una,mostramos la vista para que ingrese su contraseña
            if(!$request->session()->get("passwordSet") && $user->Password){
                return $this->setPasswordView($username);
            }

            $date = $user->PersonalData->Date ? $user->PersonalData->Date : "No posee una fecha de nacimiento";

            $name  = $user->Name;
            $email = $user->Email;
    
            return view("app.settings.account.typesConfig.accountInfo",compact("username","date","name","email"));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //antes de cambiar cualquier informacion personal del usuario, hacemos que esta ingrese su contraseña en caso de no haberla echa previamente
    public function setPasswordView($username){
        return view("app.settings.account.typesConfig.enterPassword",compact("username"));
    }

    //verificamos la contraseña del usuario
    public function setPassword(setPasswordRequest $request){
        try{
            $request->session()->put("passwordSet",true);
            $nextUrl = $request->location;
            return response()->json(["url"=>$nextUrl],200);
        }
        catch(\Exception $e){
            return response()->json(["message"=>"Ha ocurrido un error"],500);
        }
    }

    //vista para cambiar la contraseña
    public function changePasswordView(Request $request){
        $userData = User::
        select("Password","UserID","PersonalDataID")
        ->with([
            "PersonalData"=>function ($queryPersonal){
                $queryPersonal->select("Nickname");
            }
        ])
        ->where("UserID",Auth::id())
        ->first();

        $edit = true;
        $username = $userData->personalData->Nickname;

        //en caso de que aun no se haya ingresado la contraseña,y este posea una,mostramos la vista para que ingrese su contraseña
        if(!$request->session()->get("passwordSet") && $userData->Password){
            return $this->setPasswordView($username);
        }

        //permitimos poner una nueva contraseña o no
        if($userData->Password){
            return view("app.settings.account.typesConfig.accountPassword",compact("username","edit"));
        }
        $edit = false;
        return view("app.settings.account.typesConfig.accountPassword",compact("username","edit"));
    }

    public function changePassoword(changePasswordRequest $request){
        try{
            $userData = User::select("UserID","Password")->where("UserID",Auth::id())->first();

            $userData->Password = Hash::make($request->new_password);

            $userData->save();

            //eliminamos al usuario para que este pueda iniciar session de nuevo
            Auth::logout();

            return response()->json(["message"=>"Se ha cambiado la contraseña correctamente,Por favor haga click en continuar para iniciar sesion nuevamente","redirect"=>route("main")],201);
        }
        catch(\Exception $e){
            return response()->json(["error"=>"Ha ocurrido un error al cambiar la contraseña"],500);
        }
    }
}
