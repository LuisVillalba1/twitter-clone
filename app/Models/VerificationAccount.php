<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VerificationAccount extends Model
{
    use HasFactory;

    protected $primaryKey = "VerificationID";

    public function user(){
        return $this->belongsTo(User::class,"UserID");
    }

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

    public function checkCode($code){
        try{
            $email = session()->get("email");
            $user = User::where("Email",$email)->first();
            $codeVerification = $user->verificationAccount->CodeVerification;
            $codeExpired = $user->verificationAccount->Expiration;

            $now = now();

            //if the code expired
            if($now > $codeExpired){
                return response()->json(["error"=>"Expired code"],404);
            }    
            if($code == $codeVerification){
                $user->VerifiedMail = true;
                $user->save();
                session()->flush();
                return route("main");
            }
            return response()->json(['error' => 'Incorrect code,please try again'], 404);
        }
        catch(\Exception $e){
            return response()->json(["error"=>"Missing error,please try after"],404);
        }
    }
}
