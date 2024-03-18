<?php

namespace App\Models;

use App\Events\notificationEvent;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Follow extends Model
{
    use HasFactory;

    protected $primaryKey = "FollowID";

    //obtenemos informacion de los seguidos
    public function PersonalDataFollow(){
        return $this->belongsTo(PersonalData::class,"FollowerID","PersonalDataID");
    }

    //obtenemos informacion de los seguios
    public function PersonalDataFollower(){
        return $this->belongsTo(PersonalData::class,"FollowUserID","PersonalDataID");
    }


    public function checkExistFollow($userID){
        //vamos a verificar que el usuario autenticado ya haya seguido al usuario correspondiente
        $follow = Follow::
        where("FollowUserID",Auth::user()->UserID)
        ->where("FollowerID",$userID)
        ->first();

        //en caso de que ya lo haya seguido devolvemos true osino false
        if($follow){
            return $follow;
        }
        return false;
    }

    public function followOrUnfollow($username){
        //obtenemos la informacion del usuario que vamos a seguir,y verificamos si ya se estaba siguiendo o no a este
        $userToFollow = PersonalData::where("Nickname",$username)->first();
        $existFollow = $this->checkExistFollow($userToFollow->PersonalDataID);

        //si ya existe el follow lo eliminaos y retornamos el mensaje para que el usuario pueda seguir de nuevo
        if($existFollow instanceof Follow){
            $existFollow->delete();
            (new Notification())->changeNotificationFollow($userToFollow->PersonalDataID);
            return "Seguir";
        }

        //en caso de que no exista el follow lo creamos y retornamos el mensaje especifico
        $newFollow = new Follow();

        $newFollow->FollowUserID = Auth::user()->UserID;
        $newFollow->FollowerID = $userToFollow->PersonalDataID;

        $newFollow->save();

        //obtenemos el link del perfil que realiazo el seguimiento
        $linkProfile = route("showProfile",["username"=>Auth::user()->PersonalData->Nickname]);

        //enviamos al usuario correspondiente el evento de notificacion
        $this->sendNotificationFollowEvent($newFollow,$userToFollow->Nickname,Auth::user()->PersonalData->Nickname,$linkProfile);

        //creamos la notificacion para el usuario correspondiente
        (new Notification())->createNotificationFollow($userToFollow->PersonalDataID,"follow",$linkProfile);

        return "Dejar de seguir";
    }

    //obtenemos la informaicon del nuevo seguidor y enviamos la notificacion al usuario correspondiente
    public function sendNotificationFollowEvent(Follow $follow,string $username,string $userFollow,$linkProfile){
        $followData = $follow->with([
            "PersonalDataFollower"=>function ($queryPersonal){
                $queryPersonal
                ->select("PersonalDataID","Nickname")
                ->with([
                    "User"=>function ($queryUser){
                        $queryUser
                        ->select("UserID","PersonalDataID")
                        ->with([
                            "Profile"=>function ($queryProfile){
                                $queryProfile->select("ProfileID","UserID","Biography","ProfilePhotoURL","ProfilePhotoName");
                            },
                        ]);
                    },
                ]);
            },
        ])->get();

        $followData["type"] = "follow";
        $followData["link"] =  $linkProfile;

        //tenemos que convertir la informacion a un array obligatoriamente osino, se serializa las relaciones y se cargan completamente
        $dataArray = $followData->toArray();
        broadcast(new notificationEvent($dataArray,$username))->toOthers();
    }

    //obtenemos la cantidad de seguidos por parte de un usuario
    public function getCountFollows($userID){
        $follows = Follow::
        where("FollowUserID",$userID)
        ->count();

        return $follows;
    }

    //obtenemos todos los seguidores de un usuario
    public function getCountFollowers($userID){
        $followers = Follow::
        where("FollowerID",$userID)
        ->count();

        return $followers;
    }

    //obtenemos el ultimo seguidor de un usuario
    public function getLastFollower($userID){
        $follower = Follow::
        where("FollowerID",$userID)
        ->orderBy("created_at","desc")
        ->limit(1)
        ->get();

        return $follower->FollowUserID;
    }

    //obtenemos todios los seguidores de un usuario
    public function getFollows($userID){
        $follows = Follow::
        with([
            "PersonalDataFollow"=>function ($queryPersonal){
                $queryPersonal
                ->select("PersonalDataID","Nickname")
                ->with([
                    "User"=>function ($queryUser){
                        $queryUser
                        ->select("UserID","PersonalDataID")
                        ->with([
                            "Profile"=>function ($queryProfile){
                                $queryProfile->select("ProfileID","UserID","Biography","ProfilePhotoURL","ProfilePhotoName");
                            },
                        ]);
                    },
                ]);
            },
        ])
        ->where("FollowUserID",$userID)
        ->simplePaginate(1);

        return $follows;
    }

    public function getFollowers($userID){
        $followers = Follow::
        with([
            "PersonalDataFollower"=>function ($queryPersonal){
                $queryPersonal
                ->select("PersonalDataID","Nickname")
                ->with([
                    "User"=>function ($queryUser){
                        $queryUser
                        ->select("UserID","PersonalDataID")
                        ->with([
                            "Profile"=>function ($queryProfile){
                                $queryProfile->select("ProfileID","UserID","Biography","ProfilePhotoURL","ProfilePhotoName");
                            },
                        ]);
                    },
                ]);
            },
        ])
        ->where("FollowerID",$userID)
        ->simplePaginate(1);

        return $followers;
    }

}
