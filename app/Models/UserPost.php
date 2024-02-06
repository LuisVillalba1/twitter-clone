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

    //obtenemos todos los likes del post
    public function Likes(){
        return $this->hasMany(Like::class,"PostID");
    }

    //obtenemos todas sus visualizaciones
    public function Visualizations(){
        return $this->hasMany(Visualization::class,"PostID");
    }

    //obtenemos el usuario
    public function User(){
        return $this->belongsTo(User::class,"UserID");
    }

    //obtenemos todos los comentarios
    public function Comments(){
        return $this->hasMany(Comment::class,"PostID");
    }

    public function ParentID(){
        return $this->hasOne(UserPost::class,"ParentID","PostID");
    }

    //creamos un nueva post
    public function createPost($user,$message){
        try{
            $userID = $user->UserID;

            $newPost = new UserPost();
            $newPost->Message = $message;
            $newPost->UserID = $userID;
    
            $newPost->save();
    
            return $newPost->PostID;
        }
        catch(\Exception $e){
            return response()->json(["error",$e->getMessage()],500);
        }
    }

    //obtenemos todas las publicaciones
    public function getAllPublics(){
        return UserPost::all();
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
        //obtenemos el nombre del usuario
        ->with([
            "User"=>function ($queryUser){
                $queryUser->with([
                    "PersonalData"=>function($queryPersonal){
                        $queryPersonal->select("PersonalDataID","Nickname");
                    }
                ])->select("UserID","PersonalDataID");
            },
            //en caso de que posea el post contenido multimedia lo mostramos
            "MultimediaPost",
            //las interacciones que ha tenido el post
            "Interaction"=>function($queryInteraction){
                $queryInteraction
                ->select("InteractionID")
                //cantidad de likes vizualizaciones y comentarios
                ->withCount([
                    "Likes",
                    "Visualizations",
                    "Comments"
                ])
                //por cada comentario vamos a obtener las interacciones que posee este mismo
                ->with([
                    "Comments"=>function($queryComments){
                        $queryComments->with([
                            "Interaction"=>function($commentsInteracition){
                                $commentsInteracition->withCount([
                                    "Likes",
                                    "Visualizations",
                                    "Comments",
                                ])->where("InteractionID","");
                            }
                        ]);
                    }
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
