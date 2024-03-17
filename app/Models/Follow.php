<?php

namespace App\Models;

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
        $userToFollow = PersonalData::where("Nickname",$username)->first();
        $existFollow = $this->checkExistFollow($userToFollow->PersonalDataID);

        if($existFollow instanceof Follow){
            $existFollow->delete();
            return "Seguir";
        }

        $newFollow = new Follow();

        $newFollow->FollowUserID = Auth::user()->UserID;
        $newFollow->FollowerID = $userToFollow->PersonalDataID;

        $newFollow->save();

        return "Dejar de seguir";
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
