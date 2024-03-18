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

    public function deleteNotification($userID,$postID,$type){
        $notification = Notification::where("UserID",$userID)
        ->where("PostID",$postID)
        ->where("Type",$type)
        ->first()
        ->delete();
    }

    //segun el tipo de posteo obtenemos el ultimo usuario que ha interactuado(comentado o likeado) con este
    public function getLastInteracion($postID,$action){
        $interaction = DB::table("posts_notifications")
        ->select(DB::raw("count(*) as total_interactions,ActionUserID"))
        ->where("Action",$action)
        ->where("PostID",$postID)
        ->groupBy("ActionUserID")
        ->get();

        return $interaction;
    }

    //obtenemos una notificaion en concreto
    public function getNotification($userID,$postID,string $type){
        $notification =  Notification::
            where("UserID",$userID)
            ->where("PostID",$postID)
            ->where("Type",$type)
            ->first();
            return $notification;
    }

    //creamos en caso de que sea necesario la notificacion
    public function createNotificationComment($userID,$postID,$link){
        //obtenemos la notificacion en especifico
        $notification = $this->getNotification($userID,$postID,"Comment");

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
        $comments = $this->getLastInteracion($postID,"Comment");

        $lengthComments = $comments->count();

        //modificamos el link de la notificacion
        $notification->link = $link;
        $notification->PostID = $postID;
        $notification->LastUserAction = Auth::user()->UserID;
        //en caso de que mas de 2 usuarios han comentado el posteo modificamos el mensaje
        if($lengthComments == 2){
            $notification->Content = "y " . 1 . " persona mas Han comentado tu posteo";
        }
        else if($lengthComments > 2){
            $notification->Content = "y " . $lengthComments - 1 . " personas mas Han comentado tu posteo";
        }
        else{
            $notification->Content = "ha comentado tu posteo";
        }

        //guardamos la notificacion
        $notification->save();
    }


    //creamos la notificacion de like
    public function createNotificationLike($userID,$postID,$link){
        $notification = $this->getNotification($userID,$postID,"Like");

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
        $likes = $this->getLastInteracion($postID,"Like");

        $lengthLikes = $likes->count();
        
        $notification->LastUserAction = Auth::user()->UserID;
        $notification->PostID = $postID;
        
        //en caso de que mas de 2 usuarios han likeado el posteo modificamos el mensaje
        if($lengthLikes == 2){
            $notification->Content = "y " . (1) . " persona mas han indicado que le gusta tu posteo";
        }
        else if($lengthLikes > 2){
            $notification->Content = "y " . ($lengthLikes - 1) . " personas mas han indicado que le gusta tu posteo";
        }
        else{
            $notification->Content =  "ha indicado que le gusta tu posteo";
        }

        //guardamos la notificacion
        $notification->save();
    }
    
    //obtenemos la notificacion ya sea por follow o setting
    public function getNotificationType($userID,string $type){
        $notification = Notification
        ::where("UserID",$userID)
        ->where("Type",$type)
        ->first();

        return $notification;
    }

    //cambiamos el ultimo seguidor de el usuario en especifico
    public function changeNotificationFollow($userID){
        //obtenemos la notificacion
        $notification = $this->getNotificationType($userID,"follow");

        $countFollowers = (new Follow())->getCountFollowers($userID);

        //en caso de que no contenga mas seguidores, eliminamos la notificacion
        if($countFollowers == 0){
            return $notification->delete();
        }
        //obtenemos el ultimo seguidor del usuario
        $lastFollowerID = (new Follow())->getLastFollower($userID);
        $notification->LastUserAction = $lastFollowerID;

        //modificamos el mensage  dependiendo de la suma de seguidores que contenga este y lo guardamos
        if($countFollowers == 1){
            $notification->Content = "Ha empezado a seguirte";
        }
        else if($countFollowers == 2){
            $notification->Content = "y 1 persona mas han empezado a seguirte";
        }
        else{
            $notification->Content = "y " . ($countFollowers - 1) . " personas mas han empezado a seguirte";
        }

        $notification->save();
    }

    //creamos la la notificacion de follows
    public function createNotificationFollow($userID,string $type,$link){
        //obtenemos la notificacion en caso de que exista
        $notification = $this->getNotificationType($userID,$type);

        //si no existe la creamos
        if(!$notification){
            $newNotification = new Notification();
            
            $newNotification->UserID = $userID;
            $newNotification->Link = $link;
            $newNotification->Type = "Follow";
            $newNotification->LastUserAction = Auth::user()->UserID;
            $newNotification->Content = "ha empezado a seguirte";

            return $newNotification->save();
        }

        //modificamos el link del ulitmo usuario que realizo el seguimiento
        $notification->Link = $link;
        $notification->LastUserAction = Auth::user()->UserID;

        //si solo era uno el usuario que lo seguia por el momento,modificamos el mensaje a que la siguio una persona mas
        if($notification->Content == "ha empezado a seguirte"){
            $notification->Content = "y 1 persona mas han comenzado a seguirte";
        }
        //si no lo modificamos por la cantidad correcta
        else{
            $notification->Content = "y " . ((new Follow())->getCountFollowers($userID) - 1) . " personas mas han comenzado a seguirte";
        }

        $notification->save();

    }

    //cambiamos el mensaje de la notificacion con respecto a la cantidad de usuario likeado
    public function changeLikeNotificationCotent(Notification $notification,$count){
        //en caso de que sea solo uno la eliminamos
        if($count == 1){
            return $notification->delete();
        }
        if($count == 2){
            $notification->Content = "Ha comentado tu posteo";
        }
        if($count == 3){
            $notification->Content = "y 1 persona mas han indicado que le gusta tu posteo";
        }
        else{
            $notification->Content = "y " . $count - 1 . " personas mas han indicado que le gusta tu posteo";
        }

        $notification->save();
    }
}
