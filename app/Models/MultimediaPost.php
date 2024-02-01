<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class MultimediaPost extends Model
{
    use HasFactory;
    protected $table = "multimedia_posts";

    //obtenemos al post correspondiente
    public function UserPost(){
        return $this->belongsTo(UserPost::class,"PostID");
    }

    public function createMultimediaPost($images,$postID){
        //por cada imagen creamos un nuevo registro con el post correspondiente
        foreach ($images as $image) {
            //guardamos la imagen en la carpeta public
            $imageName = $image->getClientOriginalName();

            /*primero deberemos ejecutar el comando php artisan storage:link para crear un acceso directo a la carpeta storage en la carpeta public
            este se debe de ejecutar tanto en desarrollo como produccion*/

            //guardamos la imagen en la carpeta storage y obtenemos el link de la imagen
            $imageLink = $image->store("public/images");
            
            $newUrl = Storage::url($imageLink);

            //creamos un nuevo multimedia
            $newImagePost = new MultimediaPost();

            $newImagePost->Url = $newUrl;
            $newImagePost->Name = $imageName;
            $newImagePost->PostID = $postID;

            $newImagePost->save();
        }
    }
}
