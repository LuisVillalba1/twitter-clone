<?php

namespace App\Models;

use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationAccount extends Model
{
    use HasFactory;

    protected $primaryKey = "VerificationID";

    //obtenemos el usuario
    public function user(){
        return $this->belongsTo(User::class,"UserID");
    }

    //creamos un codigo de longitud 5 con una expiracion de 1 hora
    public function createCode(){
        //con mt_rand generamos un numero random de longuitud 5
        $verificationCode = mt_rand(10000, 99999);

        //agreamos la expiracion
        $expires = now()->addHours(1);

        $this->CodeVerification = $verificationCode;
        $this->Expiration = $expires;

        $this->save();

        return $this->VerificationID;
    }

    //chequeamos que el codigo ingresado sea identico al del usuario
    public function checkCode($code){
        try{
            //obtenemos al usuario
            $email = session()->get("email");
            $user = User::where("Email",$email)->first();
            //obtenemos el codigo y su expiracion
            $codeVerification = $user->verificationAccount->CodeVerification;
            $codeExpired = $user->verificationAccount->Expiration;

            $now = now();

            //if el codigo expiro
            if($now > $codeExpired){
                return response()->json(["error"=>"Expired code"],404);
            }    
            //en caso de que el codigo no haya expirado y sea el correcto lo redirigimos a la ruta main
            if($code == $codeVerification){
                $user->VerifiedMail = true;
                $user->save();
                //eliminamos los datos de session
                session()->flush();
                return route("main");
            }
            return response()->json(['error' => 'Incorrect code,please try again'], 404);
        }
        catch(\Exception $e){
            return response()->json(["error"=>"Missing error,please try after"],404);
        }
    }

    public function verifyCodeEmail($userData,$code){
            $codeExpired = $userData->verificationAccount->Expiration;
            $codeVerification = $userData->verificationAccount->CodeVerification;
            $now = now()->format('Y-m-d H:i:s');

            //if el codigo expiro
            if($now > $codeExpired){
                throw new Exception("Expired code");
            }    
            //en caso de que el codigo no haya expirado y sea el correcto lo redirigimos a la ruta main
            if(intval($code) == $codeVerification){
                $userData->VerifiedMail = true;
                $userData->save();
                //eliminamos los datos de session
                session()->forget("sendCode");
                return ;
            }
            throw new Exception("Incorrect code,please try again");
    }
}
