<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Mail\RecuperateAccountMail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use App\Mail\SendCodeMail;
use Exception;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Auth\Authenticatable as AuthenticatableTrait;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

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

    public function userPosts(){
        return $this->hasMany(UserPost::class,"PostID");
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

    public function safeAndSendEmail($request){
        try{
        //guardamos en la session el nickname y la contraseña
        session()->put("nickname",$request->input("nickname"));
        session()->put("password",$request->input("password"));
        //creamos el usuario y enviamos el mail correspondiente
        $email = session()->get("email");
        $this->createUser();
        $this->sendEmailCode($email);
        return route("singup3step");
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }

    }

    public function recuperateAccount($request){
        try{
            $user = User::where("Email",$request->input("mail"))->first();
            $id = $user->UserID;

            $linkChangePassword = URL::temporarySignedRoute(
                "changePassword",
                now()->addHours(1),
                ["id"=>Crypt::encryptString($id)]
            );

            Mail::to($request->input("mail"))
            ->send(new RecuperateAccountMail($linkChangePassword));

            return "Mail send successfully,please check your current mail account";
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }

    public function changePassword($request,$id){
        try{
            if(!$request->hasValidSignature()){
                throw new Exception("The token has expired");
            }

            $descripID = Crypt::decryptString($id);

            $user = User::where("UserID",$descripID)->first();

            $newPassword = Hash::make($request->input("password"));

            $user->Password = $newPassword;

            $user->save();

            return "Password change successfully";
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }
}
