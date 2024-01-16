<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Mail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Session;
use App\Mail\SendCodeMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class User extends Model
{
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
            $personalDataID = (new PersonalData())->createPersonalData();


            $verificationCodeID = (new verificationAccount())->createCode();

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
