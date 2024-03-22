<?php

namespace App\Models;

use App\Events\notificationEvent;
use Barryvdh\Debugbar\Facades\Debugbar;
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

    //verificamos si un usuario sigue a otro
    public function checkExistFollow($authUserID,$userID){
        //vamos a verificar que el usuario autenticado ya haya seguido al usuario correspondiente
        $follow = Follow::
        where("FollowUserID",$authUserID)
        ->select("FollowID")
        ->where("FollowerID",$userID)
        ->first();

        //en caso de que ya lo haya seguido devolvemos true osino false
        if($follow){
            return $follow;
        }
        return false;
    }

    public function followOrUnfollow($username,$authUserID){
        //obtenemos la informacion del usuario que vamos a seguir,y verificamos si ya se estaba siguiendo o no a este
        $userToFollow = PersonalData::
        where("Nickname",$username)
        ->select("PersonalDataID","Nickname")
        ->first();
        $existFollow = $this->checkExistFollow($authUserID,$userToFollow->PersonalDataID);

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
        $this->sendNotificationFollowEvent($userToFollow->Nickname,$userToFollow->PersonalDataID,$linkProfile);

        //creamos la notificacion para el usuario correspondiente
        (new Notification())->createNotificationFollow($userToFollow->PersonalDataID,"follow",$linkProfile);

        return "Dejar de seguir";
    }

    //obtenemos la informaicon del nuevo seguidor y enviamos la notificacion al usuario correspondiente
    public function sendNotificationFollowEvent(string $username,$userID,$linkProfile){
        $followData = Follow::with([
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
        ->select("FollowID","FollowUserID","FollowerID")
        ->where("FollowUserID",Auth::user()->UserID)
        ->where("FollowerID",$userID)
        ->first();

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
        ->select("FollowID")
        ->orderBy("created_at","desc")
        ->first();

        return $follower->FollowUserID;
    }

    //añadimos el link para seguir al usuario o dejar de seguir
    public function addLinkFollow($follow,$authUserID){
        //en caso de que el seguido sea el mismo que el usuario autenticado no añadimos el link para seguirlo
        if($follow->PersonalDataID == $authUserID){
            return;
        }
        $follow["linkFollow"] = route("followUser",["username"=>$follow->Nickname]);
        //en caso de que el usuario autenticado ya siga al usuario en concreto devolvemos que ya lo esta siguiendo
        if($this->checkExistFollow($authUserID,$follow->PersonalDataID)){
            $follow["followed"] = true;
        }
        else{
            $follow["followed"] = false;
        }
    }

    //obtenemos todios los seguidores de un usuario
    public function getFollows($userID){
        $authUser = Auth::user();
        $follows = Follow::
        with([
            "PersonalDataFollow"=>function ($queryPersonal){
                $queryPersonal
                ->select("PersonalDataID","Nickname")
                ->with([
                    "User"=>function ($queryUser){
                        $queryUser
                        ->select("UserID","PersonalDataID","Name")
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
        //si el usuario sigue al usuario autenticado lo mostramos como primero
        ->orderByRaw("CASE WHEN FollowerID = $authUser->UserID THEN 0 ELSE 1 END")
        ->simplePaginate(20);
        
        //añadimos el link para seguir
        foreach($follows as $follow){
            $this->addLinkFollow($follow->PersonalDataFollow,$authUser->UserID);
        }

        return $follows;
    }

    public function getFollowers($userID){
        $authUser = Auth::user();
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
        ->orderByRaw("CASE WHEN FollowerID = $authUser->UserID THEN 0 ELSE 1 END")
        ->simplePaginate(20);

        foreach ($followers as $follower){
            $this->addLinkFollow($follower->PersonalDataFollower,$authUser->UserID);
        }

        return $followers;
    }

}
