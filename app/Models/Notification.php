<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class Notification extends Model
{
    use HasFactory;

    protected $primaryKey = "NotificationID";

    public function ActionUser(){
        return $this->belongsTo(PersonalData::class,"LastUserAction");
    }

    //verificamos si ya existe la notificacion sobre el comentario de un posteo
    public function getNotificationComment($userID,$postID){
        $notification =  Notification::
            where("UserID",$userID)
            ->where("PostID",$postID)
            ->where("Type","Comment")
            ->first();
            return $notification;
    }
    //creamos en caso de que sea necesario la notificacion
    public function createNotificationComment($userID,$postID,$link){
        //obtenemos la notificacion en especifico
        $notification = $this->getNotificationComment($userID,$postID);

        //en caso de que aun no exista la notificacion, la creamos
        if(!$notification){
            $newNotification = new Notification();
            
            $newNotification->UserID = $userID;
            $newNotification->Link = $link;
            $newNotification->PostID = $postID;
            $newNotification->Type = "Comment";
            $newNotification->LastUserAction = Auth::user()->UserID;
            $newNotification->Content = "ha comentado tu posteo";

            return $newNotification->save();
        }

        //obtenemos todos todos los usuario que han comentado el posteo en especifico
        $comments = DB::table("posts_notifications")
        ->select(DB::raw("count(*) as total_comments,ActionUserID"))
        ->where("Action","Comment")
        ->where("PostID",$postID)
        ->groupBy("ActionUserID")
        ->get();

        $lengthComments = $comments->count();

        //modificamos el link de la notificacion
        $notification->link = $link;
        $notification->PostID = $postID;
        $notification->LastUserAction = Auth::user()->UserID;
        //en caso de que mas de 2 usuarios han comentado el posteo modificamos el mensaje
        if($lengthComments > 1){
            $notification->Content = "y " . $lengthComments - 1 . " personas mas Han comentado tu posteo";
        }
        else{
            $notification->Content = "ha comentado tu posteo";
        }

        //guardamos la notificacion
        $notification->save();
    }

    //verificamos si ya exista la notificacino de like
    public function getNotificationLike($userID,$postID){
        $likeNotification = Notification::
        where("PostID",$postID)
        ->where("UserID",$userID)
        ->where("Type","Like")
        ->first();

        return $likeNotification;
    }

    //creamos la notificacion de like
    public function createNotificationLike($userID,$postID,$link){
        $notification = $this->getNotificationLike($userID,$postID);

        //en caso de que aun no exista la notificacion, la creamos
        if(!$notification){
            $newNotification = new Notification();
            
            $newNotification->UserID = $userID;
            $newNotification->Link = $link;
            $newNotification->PostID = $postID;
            $newNotification->Type = "Like";
            $newNotification->LastUserAction = Auth::user()->UserID;
            $newNotification->Content = "ha indicado que le gusta tu posteo";

            return $newNotification->save();
        }
        //obtenemos todos todos los usuario que han comentado el posteo en especifico
        $likes = DB::table("posts_notifications")
        ->select(DB::raw("count(*) as total_likes,ActionUserID"))
        ->where("Action","Like")
        ->where("PostID",$postID)
        ->groupBy("ActionUserID")
        ->get();

        $lengthLikes = $likes->count();
        
        $notification->LastUserAction = Auth::user()->UserID;
        $notification->PostID = $postID;
        
        //en caso de que mas de 2 usuarios han likeado el posteo modificamos el mensaje
        if($lengthLikes > 1){
            $notification->Content = "y " . ($lengthLikes - 1) . " personas mas han indicado que le gusta tu posteo";
        }
        else{
            $notification->Content =  "ha indicado que le gusta tu posteo";
        }

        //guardamos la notificacion
        $notification->save();
    }
}
