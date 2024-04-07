<?php

namespace App\Http\Controllers;

use App\Http\Requests\Register\CodeRequest;
use App\Http\Requests\settings\changePasswordRequest;
use App\Http\Requests\settings\PersonalData\BirthdayRequest;
use App\Http\Requests\settings\setPasswordRequest;
use App\Models\PersonalData;
use App\Models\User;
use App\Models\VerificationAccount;
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

    //vista para verificar si el usuario tiene el mail verificado
    public function verifyEmailView(){
        try{
            $userData = User::select("UserID","PersonalDataID","VerifiedMail","Email")
            ->with([
                "PersonalData"=>function ($queryPersonal){
                    $queryPersonal->select("PersonalDataID","Nickname");
                }
            ])
            ->where("UserID",Auth::id())
            ->first();
            $username = $userData->PersonalData->Nickname;
            $email = $userData->Email;

            //devolvemos la vista con el valor de verified en caso de que la cuenta tenga o no el email verificado
            $verified = true;
            if($userData->VerifiedMail){
                return view("app.settings.account.typesConfig.verifyEmail",compact("username","verified","email"));
            }
            $verified = false;
            return view("app.settings.account.typesConfig.verifyEmail",compact("username","verified","email"));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //verificamos el email del usuario
    public function verifyEmailCode(CodeRequest $request){
        try{
            Debugbar::info($request->input("number5"));
            //obtenemos el codigo ingresado
            $suma = [];
            // iteramos sobre los inputs
            for($i = 1;$i <=5;$i++){
                $valor = $request->input("number{$i}");
                array_push($suma,$valor);
            }
    
            $code = implode("",$suma);

            //obtenemos la informacion del usuario,junto a su codigo
            $userData = User::select("UserID","VerificationID")
            ->with([
                "verificationAccount"=>function ($verifyCode){
                    $verifyCode->select("VerificationID","CodeVerification","Expiration");
                }
            ])
            ->where("UserID",Auth::id())->first();
            
            //vericamos si el codigo es correcto
            (new VerificationAccount())->verifyCodeEmail($userData,$code);

            return response()->json(["redirect"=>route("sendEmailVerify")],200);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()], 404);
        }
    }

    //enviamos el email para verificar la cuenta
    public function sendEmailVerify(Request $request){
        try{
            $userData = User::select("UserID","VerificationID","Email")->where("UserID",Auth::id())->first();
            $verificationID = (new VerificationAccount())->createCode();

            //creamos el codigo de verificacion
            $userData->VerificationID = $verificationID;


            $userData->save();


            //enviamos el mail con el codigo
            (new User())->sendEmailCode($userData->Email);

            //guardamos en la session que se ha enviado el codigo
            $request->session()->put("sendCode","send");

            return response()->json(["redirect"=>route("codeEmailView")],200);

        }
        catch(\Exception $e){
            Debugbar::info($e->getMessage());
            return response()->json(["errors"=>"Ha ocurrido un error al enviar el email,por favor intentelo mas tarde"],500);
        }
    }

    //vista para que se envie el codigo de verificacion
    public function verifyEmaiView(){
        try{
            $userData = User::select("UserID","PersonalDataID","VerifiedMail","Email")
            ->with([
                "PersonalData"=>function ($queryPersonal){
                    $queryPersonal->select("PersonalDataID","Nickname");
                }
            ])
            ->where("UserID",Auth::id())
            ->first();
            $username = $userData->PersonalData->Nickname;

            return view("app.settings.account.typesConfig.verifyCode.verifyCode",compact("username"));
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

    //cambiamos la contraseña del usuario
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

    //vista para permitir cambiar la fecha de nacimiento
    public function birthdayView(Request $request){
        try{
            $userData = User::
            select("UserID","PersonalDataID","Password")
            ->with([
                "PersonalData"=>function ($queryPersonal){
                    $queryPersonal->select("PersonalDataID","Nickname");
                }
            ])
            ->where("UserID",Auth::id())->first();

            $username = $userData->PersonalData->Nickname;

            //en caso de que aun no se haya ingresado la contraseña,y este posea una,mostramos la vista para que ingrese su contraseña
            if(!$request->session()->get("passwordSet") && $userData->Password){
                return $this->setPasswordView($username);
            }

            return view("app.settings.account.typesConfig.personalData.birthday",compact("username"));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //cambiamos la fecha de nacimiento del usuario
    public function changeBirthday(BirthdayRequest $request){
        try{
            $user = PersonalData::
            select("PersonalDataID","Date")
            ->where("PersonalDataID",Auth::id())->first();

            $user->Date = $request->date;

            $user->save();

            return response()->json(["message"=>"Se ha modificado la fecha correctamente","redirect"=>route("showViewAccountData")],201);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al intentar cambiar la fecha de cumpleaños"],500);
        }
    }

    //permitimos generar una contraseña al usuario en caso de que no la contenga
    public function generatePassword(Request $request){
        try{
            if(!Auth::user()->VerifiedMail){
                throw new Exception("Verifica tu cuenta");
            }
            return response()->json(["message"=>"Se ha enviado el mail a su correo electronico"],200);
        }
        catch(\Exception $e){
            if($e->getMessage() == "Verifica tu cuenta"){
                return response()->json(["errors"=>"Necesita verificar su email primero para crear una contraseña, por favor ve al apartado de 'Verifica tu cuenta'"],401);
            }
            return response()->json(["errors"=>"Ha ocurrido un error al enviar el email,por favor intentelo mas tarde"],500);
        }
    }
}
