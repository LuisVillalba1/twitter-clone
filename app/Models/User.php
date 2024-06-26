<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Http\Requests\settings\EditProfileRequest;
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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\URL;

class User extends Model implements Authenticatable
{
    use AuthenticatableTrait;
    use HasFactory;

    protected $table = "users";
    protected $primaryKey = "UserID";

    //obtenemos la informacion personal
    public function personalData(){
        return $this->hasOne(PersonalData::class,"PersonalDataID");
    }

    //obtenemos la verficiacion de cuenta
    public function verificationAccount(){
        return $this->hasOne(VerificationAccount::class,"VerificationID","VerificationID");
    }

    //obtenemos los posts del usuario
    public function userPosts(){
        return $this->hasMany(UserPost::class,"UserID");
    }

    public function Profile(){
        return $this->hasOne(Profile::class,"ProfileID");
    }

    public function PostNotification(){
        return $this->hasMany(PostsNotification::class,"UserID");
    }

    //guardamos en los datos de session su nombre email y fecha de nacimiento
    public function safePersonalDate($request){
        Session::put("name",$request->input("name"));
        Session::put("email",$request->input("email"));
        Session::put("date",$request->input("date"));

        return route("singup2step");
    }

    //Validamos el mail del usuario
    public function sendEmailCode($email){
        //buscamos el codigo de verificacion del usuario y se lo enviamos
        $user = User::where("Email",$email)->first();
        $code = $user->verificationAccount->CodeVerification;
        Mail::to($email)
        ->send(new SendCodeMail($code));
    }

    public function createUser(){
        //create a a new personal data
        $personalDataID = (new PersonalData())->createPersonalData();

        //creamos un nuevo codigo de verificacion para luego enviar al usuario por mail
        $verificationCodeID = (new verificationAccount())->createCode();

        //creamos un nuevo usuario
        $user = new User();
        $user->Name = session()->get("name");
        $user->Email = session()->get("email");
        //hasheamos la contraseña para guardarlo en nuestra base de datos
        $user->Password = Hash::make(session()->get("password"));
        $user->PersonalDataID = $personalDataID;
        $user->VerificationID = $verificationCodeID;

        $user->save();

        //a partir del usuario creamos un nuevo profile

        (new Profile())->createProfile($user->UserID);

        return $verificationCodeID;
    }

    //guardamos el nickname ingresado junto a su contraseña
    public function safeAndSendEmail($request){
        try{
        //guardamos en la session el nickname y la contraseña
        session()->put("nickname",$request->input("nickname"));
        session()->put("password",$request->input("password"));
        //creamos el usuario y enviamos el mail correspondiente para poder validar su mail
        $email = session()->get("email");
        $this->createUser();
        $this->sendEmailCode($email);
        return route("singup3step");
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }

    }

    //verificamos is existe el usuario con el mail ingresado y enviamos el mail
    public function recuperateAccount($request){
        try{
            //buscamos el usuario
            $user = User::where("Email",$request->input("mail"))->first();
            $id = $user->UserID;

            //creamos un link con expiracion de 1 hora que contenga el userID
            $linkChangePassword = URL::temporarySignedRoute(
                "changePassword",
                now()->addHours(1),
                ["id"=>Crypt::encryptString($id)]
            );

            //enviamos el mail con el link para poder modificar la contraseña
            Mail::to($request->input("mail"))
            ->send(new RecuperateAccountMail($linkChangePassword));

            return "Mail send successfully,please check your current mail account";
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }

    //cambiamos la contraseña del usuario
    public function changePassword($request,$id){
        try{
            //verificamos de nuevo que la ruta no tenga una firma invalida
            if(!$request->hasValidSignature()){
                throw new Exception("The token has expired");
            }

            //obtenemos el usuario
            $descripID = Crypt::decryptString($id);

            $user = User::where("UserID",$descripID)->first();

            //guardamos la nueva contraseña
            $newPassword = Hash::make($request->input("password"));

            $user->Password = $newPassword;

            $user->save();

            return "Password change successfully";
        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],500);
        }
    }
    
    //obtenemos todos los posteos del usuario autenticado
    public function getUserPosts($userName,$personalDataID){
            $user = Auth::user();
            $userID = $user->UserID;
            
    
            //obtenemos su contenido multimedia
            $posts =  UserPost::with([
                "MultimediaPost",
                //verificamos si el usuario logeado ya ha visualizado o likeado el post
                "Likes"=>function($queryLike) use ($userID){
                    $queryLike->where("NicknameID",$userID);
                },
                "Visualizations"=>function($queryVisualization) use ($userID){
                    $queryVisualization->where("NicknameID",$userID);
                },
                //obtenemos datos del usuario correspondiente al post
                "User"=>function($queryUser){
                    $queryUser->select("UserID","PersonalDataID")
                    ->with([
                        "PersonalData"=>function($queryPersonal){
                            $queryPersonal->select("PersonalDataID","Nickname");
                        },
                        "Profile"=>function ($queryProfile){
                            $queryProfile->select("ProfileID","UserID","ProfilePhotoURL","ProfilePhotoName");
                        }
                    ]);
                },
                "Comments"=>function($queryComments) use ($userID){
                    $queryComments->where("UserID",$userID);
                }
            ])
            //mostramos la cantidad de interacciones que contiene el post
            ->withCount([
                "Likes",
                "Visualizations",
                "Comments"
            ])
            ->where("ParentID",null)
            ->where("UserID",$personalDataID)
            ->orderBy("PostID","desc")
            ->simplePaginate(15);

            foreach($posts as $post){
                //mostramos los links para poder interactuar con cada post
                $userName = $post->User->PersonalData->Nickname;
                $idEncrypt = Crypt::encryptString($post->PostID);
                $post["linkLike"] = route("likePost",["username"=>$userName,"encryptID"=>$idEncrypt]) ?? null;
                $post["linkComment"] = route("commentPostView",["username"=>$userName,"encryptID"=>$idEncrypt]);
                $post["linkPost"] = route("showPost",["username"=>$userName,"encryptID"=>$idEncrypt]);
            }

            return $posts;
    }

    //recibimos un posteo y le agregamos el nickname junto a las fotos de su perfil
    //tambien le agregamos el link del posteo correspondiente
    public function addUserContent($id){
        $userLikeData = User::
        select("UserID","PersonalDataID")
        ->with([
            "personalData"=>function ($queryPersonal){
                $queryPersonal->select("PersonalDataID","Nickname");
            },
            "Profile"=>function ($queryProfile){
                $queryProfile->select("ProfileID","UserID","ProfilePhotoURL","ProfilePhotoName");
            }
        ])
        ->where("UserID",$id)
        ->first();

        return $userLikeData;
    }

}
