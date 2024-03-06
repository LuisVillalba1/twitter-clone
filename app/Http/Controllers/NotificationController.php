<?php

namespace App\Http\Controllers;

use App\Models\PostsNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            PostsNotification::
            select("PostNotificationID","UserID","PostID","LinkPost","ActionUserID","Action","Visualizated")
            ->with([
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
            ->where("UserID",Auth::user()->UserID)
            ->get();

            return $notifications;
        }
        catch(\Exception $e){
            return response()->json(["Errors"=>$e->getMessage()],500);
        }
    }
}
