<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    //
    //mostramos la vista de notificaciones
    public function showNotifications(){
        try{
            $user= Auth::user();
            $name = $user->Name;
            $nickname =  $user->PersonalData->Nickname;
            $profilePhoto = [$user->Profile->ProfilePhotoURL,$user->Profile->ProfilePhotoName];

            return view("app.notifications.notification",compact(["name","nickname","profilePhoto"]));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }
}
