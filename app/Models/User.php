<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\SendCodeMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use HasFactory;

    protected $table = "users";
    protected $primaryKey = "UserID";

    public function personalData(){
        return $this->hasOne(PersonalData::class,"PersonalDataID","PersonalDataID");
    }

    public function verificationAccount(){
        return $this->hasOne(VerificationAccount::class,"VerificationID","VerificationID");
    }

    public function safePersonalDate($request){
        Session::put("name",$request->input("name"));
        Session::put("email",$request->input("email"));
        Session::put("date",$request->input("date"));

        return route("singup2step");
    }

    public function sendEmailCode($email){
        $user = User::where("Email",$email)->first();
        $code = $user->verificationAccount->CodeVerification;
        Mail::to($email)
        ->send(new SendCodeMail($code));
    }

    public function createUser(){
        try{
            //create a a new personal data
            $personalDataID = (new PersonalData())->createPersonalData();

            //create a verification code for user email
            $verificationCodeID = (new verificationAccount())->createCode();

            //create a new user
            $user = new User();
            $user->Name = session()->get("name");
            $user->Email = session()->get("email");
            $user->Password = Hash::make(session()->get("password"));
            $user->PersonalDataID = $personalDataID;
            $user->VerificationID = $verificationCodeID;

            $user->save();

            return $verificationCodeID;
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e,500]);
        }
    }

    public function safeAndSendEmail($request){
        //guardamos en la session el nickname y la contraseña
        session()->put("nickname",$request->input("nickname"));
        session()->put("password",$request->input("password"));
        
        //creamos el usuario y enviamos el mail correspondiente
        $email = session()->get("email");
        $this->createUser();
        $this->sendEmailCode($email);

        return route("singup3step");
    }

}
