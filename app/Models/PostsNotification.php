<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PostsNotification extends Model
{
    use HasFactory;

    protected $primaryKey = "PostNotificationID";

    //verificamos si ya exista la notificacion
    public function checkExistNotification($postID,$action){
        //en caso de que ya exista la notificacion devolvemos el link del posteo
        $postNotifcation = PostsNotification::
            where("PostID",$postID)
            ->where("Action",$action)
            ->first();
        if($postNotifcation){
            return $postNotifcation->LinkPost;
        }
        return false;
    }

    //creamos una nueva notificacion
    public function createNotification($postID,$userID,$action,$username){
        try{
            $authUser = Auth::user();
            //verificamos si ya existe el posteo y en caso de que exista obtenemos el link del posteo
            $linkPost = $this->checkExistNotification($postID,$action);
    
            $newNotification = new PostsNotification();
            $newNotification->UserID = $userID;
            $newNotification->PostID = $postID;
            $newNotification->ActionUserID = $authUser->UserID;
            $newNotification->Action = $action;
    
            //agregamos el link del posteo correspondiente y guardamos la notificacion
            if($linkPost){
                $newNotification->LinkPost = $linkPost;
            }
            else{
                $idEncrypt = Crypt::encryptString($postID);
                $newNotification->LinkPost = route("showPost",["username"=>$username,"encryptID"=>$idEncrypt]);
            }
    
            $newNotification->save();
        }
        catch(\Exception $e){
            throw new Exception($e->getMessage());
        }
    }
}
