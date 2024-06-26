<?php

namespace App\Models;

use App\Http\Controllers\CookiesController;
use App\Http\Requests\Content\MinID;
use Barryvdh\Debugbar\Facades\Debugbar;
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

    public function showProfile($username,$typeProfileContent,$request){
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
        ->select("PersonalDataID","Nickname")
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

        //obtenemos la cantidad de seguidores y seguidos
        $follows = (new Follow())->getCountFollows($profile->PersonalDataID);
        $followers = (new Follow())->getCountFollowers($profile->PersonalDataID);

        //verficamos si el usuario autenticado sigue al usuario
        $follow = (new Follow())->checkExistFollow($userID,$profile->PersonalDataID);
        //si el perfil al que acceder es el mismo que el que esta logeado, permitimos a este editar su perfil
        if($profile->PersonalDataID == $userID){
            $edit = true;
            return view("app.profile." . $typeProfileContent,compact(["profile","created","edit","follow","follows","followers"]));
        }
        $edit = false;

        //guardamos el usuario en la cookie
        $cookie = (new CookiesController())->setCookieSearch($profile->Nickname,"User",$request);

        //si no mostramos el perfil del usuario
        return response()->view("app.profile.".$typeProfileContent,compact(["profile","created","edit","follow","follows","followers"]))->withCookie($cookie);
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

            //modificamos los valores de la imagen de perfil del usuario auntenticado
            Auth::user()->Profile->CoverPhotoURL = $coverPhotoData[1];
            Auth::user()->Profile->CoverPhotoName = $coverPhotoData[0];
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

    public function getAnswers($username,$personalDataID){
        $user = Auth::user();

        $userID = $user->UserID;

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
                    },
                    "Profile"=>function ($queryProfile){
                        $queryProfile->select("ProfileID","UserID","ProfilePhotoURL","ProfilePhotoName");
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
                            },
                            "Profile"=>function ($queryProfile){
                                $queryProfile->select("ProfileID","UserID","ProfilePhotoURL","ProfilePhotoName");
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

            //seteamos los link de los padres
            $usernameParent = $post->Parent->User->PersonalData->Nickname;
            $postIDParent = $post->Parent->PostID;

            $post->Parent["linkPost"] = route("showPost",["username"=>$usernameParent,"encryptID"=>Crypt::encryptString($postIDParent)]);
            $post->Parent["linkUser"] = route("showProfile",["username"=>$usernameParent]);
        }

        return $posts;
    }

    //obtenemos todos los perfiles que sean iguales o contengan como nickname un valor en especifico
    public function getProfiles($nickname,$authUserID){
        $queryWhere = "%" . $nickname . "%";
        $profiles = PersonalData::
        select("PersonalDataID","Nickname")
        ->with([
            "user"=>function ($queryUser){
                $queryUser
                ->select("UserID","Name",)
                ->with([
                    "Profile"=>function ($queryProfile){
                        $queryProfile->select("ProfileID","UserID","Biography","CoverPhotoURL","ProfilePhotoURL","CoverPhotoName","ProfilePhotoName");
                    }
                ]);
            }
        ])
        ->where("Nickname","like",$queryWhere)
        ->simplePaginate(15);

        foreach($profiles as $profile){
            (new Follow())->addLinkFollow($profile,$authUserID);
        }

        $response = [
            "type" => "profiles",
            "data" => $profiles
        ];


        return $response;
    }
}
