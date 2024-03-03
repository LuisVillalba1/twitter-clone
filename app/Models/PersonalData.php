<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

use function Termwind\render;

class PersonalData extends Model
{
    use HasFactory;

    protected $primaryKey = "PersonalDataID";

    //obtenemos al usuario correspondiente
    public function user(){
        return $this->belongsTo(User::class,"PersonalDataID");
    }

    public function getUserId($username){
        $user = PersonalData::where("Nickname",$username)->first();

        if($user){
            return response()->json(["errors"=>"No se ha encontrado el id del usuario"],404);
        }
        return $user->PersonalDataID;
    }

    public function createPersonalData(){
        $newDatata = new PersonalData();

        $newDatata->Nickname = session()->get("nickname");
        $newDatata->Date = session()->get("date");

        $newDatata->save();

        return $newDatata->PersonalDataID;
    }

    //segun un nombre de usuario checkeamos si existe o no 
    public function checkUsername($userName){
        $personalData = PersonalData::where("Nickname",$userName)->first();

        if(!$personalData){
            return redirect()->route("errorPage");
        }
        return $personalData;
    }

    //mostramos el perfil del usuario
    public function showProfile($username){
        try{
        //obtenemos el perfil
        $profile = PersonalData::
        where("Nickname",$username)
        ->with([
            "user"=>function ($queryUser){
                $queryUser->select("UserID","Name","created_at");
            }
        ])
        ->first();

        $user = Auth::user();

        $userID = $user->UserID;

        //en caso de que no exista el usuario lo enviamos a nuestra pagina de error
        if(!$profile){
            session()->put("error","No se ha encontrado la ruta solicitada");
            return redirect()->route("errorPage");
        }

        $name = $profile->user->Name;
        $created = $profile->user->created_at;
        
        //cambiamos el formato del date
        $fecha_objeto = new DateTime($created);
        $created = $fecha_objeto->format('d/m/Y');

        //si el perfil al que acceder es el mismo que el que esta logeado, permitimos a este editar su perfil
        if($profile->PersonalDataID == $userID){
            $edit = true;
            return view("app.profile.profile",compact(["username","name","created","edit"]));
        }
        //si no mostramos el perfil del usuario
        return view("app.profile.profile",compact(["username","name","created"]));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }
}
