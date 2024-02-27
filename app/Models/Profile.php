<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $primaryKey = "ProfileID";

    public function showProfile($username,$typeProfileContent){
        //obtenemos el perfil
        $profile = PersonalData::
        where("Nickname",$username)
        ->with([
            "user"=>function ($queryUser){
                $queryUser
                ->select("UserID","Name","created_at")
                ->with([
                    "Profile"=>function ($queryProfile){
                        $queryProfile->select("ProfileID","UserID","Biography","CoverPhotoURL","ProfilePhotoURL","CoverPhotoName","ProfilePhotoName");
                    }
                ]);
            }
        ])
        ->first();
    
        $user = Auth::user();
    
        $userID = $user->UserID;
    
        //en caso de que no exista el usuario lo enviamos a nuestra pagina de error
        if(!$profile){
            session()->put("error","No se ha encontrado la ruta solicitada");
            return redirect()->route("errorPage");
        }
    
        $name = $profile->user->Name;
        $created = $profile->user->created_at;
            
        //cambiamos el formato del date
        $fecha_objeto = new DateTime($created);
        $created = $fecha_objeto->format('d/m/Y');
    
        //si el perfil al que acceder es el mismo que el que esta logeado, permitimos a este editar su perfil
        if($profile->PersonalDataID == $userID){
            $edit = true;
            return view("app.profile." . $typeProfileContent,compact(["profile","created","edit"]));
        }
        //si no mostramos el perfil del usuario
        return view("app.profile.".$typeProfileContent,compact(["username","name","created"]));
    }

    //creamos un nuevo perfil
    public function createProfile($userID){
        $newProfileData = new Profile();

        $newProfileData->UserID = $userID;

        $newProfileData->save();
    }

    public function modifyProfile($profile,$data){
        //en caso de existir guardamos la biografia del usuario
        if($data->bio){
            $profile->Biography = $data->bio;
        }

        //guardamos las imagenes tanto de portada como de perfil en caso de exisitir
        if($data->coverPhoto){
            $coverPhotoData = $this->savePhoto($data->file("coverPhoto"),"coverPhoto");

            $profile->CoverPhotoURL = $coverPhotoData[1];
            $profile->CoverPhotoName = $coverPhotoData[0];
        }

        if($data->profilePhoto){
            $profilePhotoData = $this->savePhoto($data->file("profilePhoto"),"profilePhoto");

            $profile->ProfilePhotoURL = $profilePhotoData[1];
            $profile->ProfilePhotoName = $profilePhotoData[0];
        }

        $profile->save();
    }

    //guardamos la foto en nuestro sotorage
    public function savePhoto($image,$typePhoto){
        $user = PersonalData::where("PersonalDataID",Auth::user()->UserID)->first();

        //a la imagen la guardamos segun la fecha actual mas el nombre del usuario
        $imageName =  uniqid($user->Nickname . $typePhoto);

        //guardamos la imagen en nuestro sotorage
        $imageLink = $image->store("public/images");
            
        $newUrl = Storage::url($imageLink);

        return [$imageName,$newUrl];
    }

    public function getAnswers($username){
        $user = Auth::user();

        $userID = $user->UserID;

        //obtenemos el usuario al que se quiere obtener los posteos
        $personalData = PersonalData::where("Nickname",$username)->first();

        //obtenemos los posteos donde hayan sido respuestas 
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
                    }
                ]);
            },
            "Comments"=>function($queryComments) use ($userID){
                $queryComments->where("UserID",$userID);
            },
            "Parent"=>function($queryParent){
                $queryParent
                ->select("Message","PostID","UserID")
                ->with([
                    "User"=>function($parentUser){
                        $parentUser
                        ->select("UserID","PersonalDataID")
                        ->with([
                            "PersonalData"=>function($parentPersonal){
                                $parentPersonal->select("PersonalDataID","Nickname");
                            }
                        ]);
                    },
                    "MultimediaPost"
                ]);
            }
        ])
        //mostramos la cantidad de interacciones que contiene el post
        ->withCount([
            "Likes",
            "Visualizations",
            "Comments"
        ])
        ->whereNotNull('ParentID')
        ->where("UserID",$personalData->PersonalDataID)
        ->orderBy("PostID","desc")
        ->get();

        foreach($posts as $post){
            //mostramos los links para poder interactuar con cada post
            $userName = $post->User->PersonalData->Nickname;
            $idEncrypt = Crypt::encryptString($post->PostID);
            $post["linkLike"] = route("likePost",["username"=>$userName,"encryptID"=>$idEncrypt]) ?? null;
            $post["linkComment"] = route("commentPostView",["username"=>$userName,"encryptID"=>$idEncrypt]);
            $post["linkPost"] = route("showPost",["username"=>$userName,"encryptID"=>$idEncrypt]);

            //seteamos los link de los padres
            $usernameParent = $post->Parent->User->PersonalData->Nickname;
            $postIDParent = $post->Parent->PostID;

            $post->Parent["linkPost"] = route("showPost",["username"=>$usernameParent,"encryptID"=>Crypt::encryptString($postIDParent)]);
            $post->Parent["linkUser"] = route("showProfile",["username"=>$usernameParent]);
        }

        return $posts;
    }
}
