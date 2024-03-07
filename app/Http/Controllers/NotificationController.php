<?php

namespace App\Http\Controllers;

use App\Models\PostsNotification;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use PhpParser\Node\Expr\Cast\Array_;

class NotificationController extends Controller
{
    //
    //mostramos la vista de notificaciones
    public function showNotifications(){
        try{
            //obtenemos ciertos datos de la aunteticacion del usuario
            $user = Auth::user();
            $name = $user->Name;
            $nickname =  $user->PersonalData->Nickname;
            $profilePhoto = [$user->Profile->ProfilePhotoURL,$user->Profile->ProfilePhotoName];

            return view("app.notifications.notification",compact(["name","nickname","profilePhoto"]));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //obtenemos las notificaciones del usuario autenticado que aun no han sido vizualizadas
    public function getNotifications(){
        try{
            $notifications = 
            DB::table('posts_notifications as p')
            ->select("p.PostID","up.Message",
            DB::raw('(SELECT Url from multimedia_posts where PostID = p.PostID LIMIT 1) as Multimedia'),
            DB::raw('(SELECT ActionUserID from posts_notifications WHERE Action = "Like" AND PostID = p.PostID ORDER BY created_at DESC LIMIT 1) AS LastUserLike'),
            DB::raw('COUNT(CASE WHEN Action = "Like" THEN 1 END) AS SumLikes'),
            DB::raw('(SELECT ActionUserID from posts_notifications WHERE Action = "Comment" AND PostID = p.PostID ORDER BY created_at DESC LIMIT 1) AS LastUserComment'),
            DB::raw('COUNT(CASE WHEN Action = "Comment" THEN 1 END) AS SumComments')
            )
            ->join("user_posts as up","p.PostID","=","up.PostID")
            ->where("p.UserID", Auth::user()->UserID)
            ->groupBy("p.PostID")
            ->get();

            $notifications = $this->userNotificationContent($notifications);

            return $notifications;
        }
        catch(\Exception $e){
            return response()->json(["Errors"=>$e->getMessage()],500);
        }
    }

    //vamos a obtener un array de posteos, por cada uno vamos a agregarle la informacion del ultimo usuario que likeo y comento
    public function userNotificationContent($postNotifications){
        foreach($postNotifications as $postNotification){
            $postNotification->LastUserLike = (new User())->addUserContent($postNotification->LastUserLike);
            $postNotification->LastUserComment = (new User())->addUserContent($postNotification->LastUserComment);
        }

        return $postNotifications;
    }
}
