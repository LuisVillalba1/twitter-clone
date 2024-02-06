<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
        $user = PersonalData::where("Nickname",$userName)->first();

        if(!$user){
            return redirect()->route("errorPage");
        }
    }
}
