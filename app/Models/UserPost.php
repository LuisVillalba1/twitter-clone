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

    //mostramos la vista para un post
    public function showPost($username,$encryptID){
        try{
            //chekeamos que exista el usuario y el post
            (new PersonalData())->checkUsername($username);

            $postID = Crypt::decryptString($encryptID);

            (new UserPost())->checkPostID($postID);

            return view("app.posts.userPost");
        }
        catch(\Exception $e){
            return $e->getMessage();
            return redirect()->route("errorPage");
        }
    }

    public function getPostData($username,$encryptID){
        try{
                    //chekeamos que exista el usuario y el post
        (new PersonalData())->checkUsername($username);

        $postID = Crypt::decryptString($encryptID);

        (new UserPost())->checkPostID($postID);
        
        //obtenemos ciertos datos del post
        return UserPost::
        select("PostID","UserID","Message","InteractionID")
        ->with([
            "User"=>function ($queryUser){
                $queryUser->with([
                    "PersonalData"=>function($queryPersonal){
                        $queryPersonal->select("PersonalDataID","Nickname");
                    }
                ])->select("UserID","PersonalDataID");
            },
            "MultimediaPost",
            "Interaction"=>function($queryInteraction){
                $queryInteraction
                ->select("InteractionID")
                ->withCount([
                    "Likes",
                    "Visualizations",
                    "Comments"
                ])
                ->with([
                    "Comments"
                ]);
            }
        ])
        ->where("PostID",$postID)->first();
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al obtener el post"],500);
        }

    }

    //segun un postID chekeamos si existe el post o no
    public function checkPostID($postID){
        $post = UserPost::where("PostID",$postID)->first();

        if(!$post){
            return redirect()->route("errorPage");
        }
    }

}
