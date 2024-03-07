<?php

namespace App\Models;

use App\Events\notificationEvent;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PostsNotification extends Model
{
    use HasFactory;

    protected $primaryKey = "PostNotificationID";

    public function User(){
        return $this->belongsTo(User::class,"UserID");
    }

    public function Post(){
        return $this->belongsTo(UserPost::class,"PostID");
    }

    public function ActionUser(){
        return $this->belongsTo(User::class,"ActionUserID");
    }

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
    public function createNotificationLike($postID,$userID,$username){
        try{
            $authUser = Auth::user();
            //verificamos si ya existe el posteo y en caso de que exista obtenemos el link del posteo
            $linkPost = $this->checkExistNotification($postID,"Like");
    
            $newNotification = new PostsNotification();
            $newNotification->UserID = $userID;
            $newNotification->PostID = $postID;
            $newNotification->ActionUserID = $authUser->UserID;
            $newNotification->Action = "Like";
    
            //agregamos el link del posteo correspondiente y guardamos la notificacion
            if($linkPost){
                $newNotification->LinkPost = $linkPost;
            }
            else{
                $idEncrypt = Crypt::encryptString($postID);
                $newNotification->LinkPost = route("showPost",["username"=>$username,"encryptID"=>$idEncrypt]);
            }
    
            $newNotification->save();

            //obtenemos la informacion de la notificacion
            $data = $this->getNotificationData($newNotification->PostNotificationID);

            //se la enviamos al usuario
            $this->sendNotification($data,$username);

        }
        catch(\Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    //vamos a recibir el id del posteo que se comento junto al id del usuario,
    public function createNotificationComment($postUserID,$userID,$username,$commentID,$commentUser){
        try{
            $authUser = Auth::user();

            $newNotification = new PostsNotification();
            $newNotification->UserID = $userID;
            $newNotification->PostID = $postUserID;
            $newNotification->ActionUserID = $authUser->UserID;
            $newNotification->Action = "Comment";

            $idEncrypt = Crypt::encryptString($commentID);
            $newNotification->LinkPost = route("showPost",["username"=>$commentUser,"encryptID"=>$idEncrypt]);

            $newNotification->save();

            //obtenemos la informacion de la notificacion
            $data = $this->getNotificationData($newNotification->PostNotificationID);

            //se la enviamos al usuario
            $this->sendNotification($data,$username);

        }
        catch(\Exception $e){
            throw new Exception($e->getMessage());
        }
    }

    public function getNotificationData($PostNotificationID){
        $data = PostsNotification::
            select("Action","PostNotificationID","UserID","LinkPost","ActionUserID","PostID")
            ->with([
                "User"=>function ($queryUser){
                    $queryUser
                    ->select("UserID","PersonalDataID")
                    ->with([
                        "personalData"=>function ($queryPersonal){
                            $queryPersonal->select("PersonalDataID","Nickname");
                        }
                    ]);
                },
                //obtenemos datos del usuario que ha realizado la interaccion
                "ActionUser"=>function ($queryUser){
                    $queryUser
                    ->select("UserID","PersonalDataID")
                    ->with([
                        "personalData"=>function($queryPersonalData){
                            $queryPersonalData->select("PersonalDataID","Nickname");
                        },
                        "Profile"=>function($queryProfile){
                            $queryProfile->select("ProfileID","UserID","ProfilePhotoURL","ProfilePhotoName");
                        }
                    ]);
                },
                //obtenemos ciertos datos como el mensage y el contenido multimedia del posteo
                "Post"=>function ($queryPost){
                    $queryPost
                    ->select("PostID","Message")
                    ->with([
                        "MultimediaPost"=>function ($queryMultimedia){
                            $queryMultimedia->select("MultimediaID","Name","Url");
                        }
                    ]);
                }
                            
            ])
            ->where("PostNotificationID",$PostNotificationID)
            ->first();

        return $data;
    }

    //enviamos la notificacion
    public function sendNotification($data,$username){
        //tenemos que convertir la informacion a un array obligatoriamente osino, se serializa las relaciones y se cargan completamente
        $dataArray = $data->toArray();
        broadcast(new notificationEvent($dataArray,$username))->toOthers();
    }

}


