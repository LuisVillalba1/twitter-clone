<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

use function PHPSTORM_META\map;

class UserPost extends Model
{
    use HasFactory;

    protected $primaryKey = "PostID";

    //obtenemos las imagenes multimedia
    public function MultimediaPost(){
        return $this->hasMany(MultimediaPost::class,"PostID");
    }

    //obtenemos el usuario
    public function User(){
        return $this->belongsTo(User::class,"UserID");
    }

    //obtenemos la interaccion correspondiente
    public function Interaction(){
        return $this->belongsTo(PostsInteraction::class,"InteractionID");
    }

    //creamos un nueva post
    public function createPost($user,$message){
        try{
            $userID = $user->UserID;

            $newPost = new UserPost();
            $newPost->Message = $message;
            $newPost->UserID = $userID;
            //junto a su nuevo post interaccion que nos servira para ver las interacciones que recibio ese post
            $newPost->InteractionID = (new PostsInteraction())->createInteraction();
    
            $newPost->save();
    
            return $newPost->PostID;
        }
        catch(\Exception $e){
            return response()->json(["error",$e->getMessage()],500);
        }
    }

    //obtenemos todas las publicaciones
    public function getAllPublics(){
        //utilizaremos las post interacctions ya que nos brindara una query mas simple
        $interacciones = (new PostsInteraction())->getPublicInteractions();

        return $interacciones;
    }
}
