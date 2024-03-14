<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Follow extends Model
{
    use HasFactory;

    protected $primaryKey = "FollowID";

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
}
