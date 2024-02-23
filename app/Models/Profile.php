<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Profile extends Model
{
    use HasFactory;

    protected $primaryKey = "ProfileID";

    //creamos un nuevo perfil
    public function createProfile($userID){
        $newProfileData = new Profile();

        $newProfileData->UserID = $userID;

        $newProfileData->save();
    }

    public function modifyProfile($profile,$data){
        //en caso de existir guardamos la biografia del usuario
        if($data->bio){
            $profile->Biography = $data->bio;
        }

        //guardamos las imagenes tanto de portada como de perfil en caso de exisitir
        if($data->coverPhoto){
            $coverPhotoData = $this->savePhoto($data->file("coverPhoto"),"coverPhoto");

            $profile->CoverPhotoURL = $coverPhotoData[1];
            $profile->CoverPhotoName = $coverPhotoData[0];
        }

        if($data->profilePhoto){
            $profilePhotoData = $this->savePhoto($data->file("profilePhoto"),"profilePhoto");

            $profile->ProfilePhotoURL = $profilePhotoData[1];
            $profile->ProfilePhotoName = $profilePhotoData[0];
        }

        $profile->save();
    }

    //guardamos la foto en nuestro sotorage
    public function savePhoto($image,$typePhoto){
        $user = PersonalData::where("PersonalDataID",Auth::user()->UserID)->first();
        $fechaActual = Carbon::now();

        //a la imagen la guardamos segun la fecha actual mas el nombre del usuario
        $imageName =  uniqid($user->Nickname . $typePhoto);

        //guardamos la imagen en nuestro sotorage
        $imageLink = $image->store("public/images");
            
        $newUrl = Storage::url($imageLink);

        return [$imageName,$newUrl];

    }
}
