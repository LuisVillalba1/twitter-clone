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
        return $this->hasMany(UserPost::class,"ParentID","PostID");
    }

    //obtenemos el parentID
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
        $user = Auth::user();
        $userID = $user->UserID;

        //obtenemos su contenido multimedia
        $posts = UserPost::with([
            "MultimediaPost",
            //verificamos si el usuario logeado ya ha visualizado o likeado el post
            "Likes"=>function($queryLike) use ($userID){
                $queryLike->where("NicknameID",$userID);
            },
            "Visualizations"=>function($queryVisualization) use ($userID){
                $queryVisualization->where("NicknameID",$userID);
            },
            //obtenemos datos del usuario correspondiente al post
            "User"=>function($queryUser){
                $queryUser->select("UserID","PersonalDataID")
                ->with([
                    "PersonalData"=>function($queryPersonal){
                        $queryPersonal->select("PersonalDataID","Nickname");
                    }
                ]);
            }
        ])
        //mostramos la cantidad de interacciones que contiene el post
        ->withCount([
            "Likes",
            "Visualizations",
            "Comments"
        ])
        ->where("ParentID",null)
        ->get();

        foreach($posts as $post){
            //mostramos los links para poder interactuar con cada post
            $userName = $post->User->PersonalData->Nickname;
            $idEncrypt = Crypt::encryptString($post->PostID);
            $post["linkLike"] = route("likePost",["username"=>$userName,"encryptID"=>$idEncrypt]) ?? null;
            $post["linkVisualization"] = route("VisualizationPost",["username"=>$userName,"encryptID"=>$idEncrypt]);
            $post["linkComment"] = route("commentPostView",["username"=>$userName,"encryptID"=>$idEncrypt]);
            $post["linkPost"] = route("showPost",["username"=>$userName,"encryptID"=>$idEncrypt]);
        }

        return $posts;
    }

    //mostramos la vista para un post
    public function showPost($username,$encryptID){
        try{
            //chekeamos que exista el usuario y el post
            (new PersonalData())->checkUsername($username);

            $postID = Crypt::decryptString($encryptID);

            (new UserPost())->checkPostID($postID);

            //mostramos la vista del post
            return view("app.posts.userPost");
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    public function setLinksInteraction($post){
        $userName = $post->User->PersonalData->Nickname;
        $idEncrypt = Crypt::encryptString($post->PostID);
        $post["linkLike"] = route("likePost",["username"=>$userName,"encryptID"=>$idEncrypt]) ?? null;
        $post["linkVisualization"] = route("VisualizationPost",["username"=>$userName,"encryptID"=>$idEncrypt]);
        $post["linkComment"] = route("commentPostView",["username"=>$userName,"encryptID"=>$idEncrypt]);
        $post["linkPost"] = route("showPost",["username"=>$userName,"encryptID"=>$idEncrypt]);

        return $post;
    }

    public function getPostData($username,$encryptID){
        try{
        //obtenemso los datos del usuario
        $user = Auth::user();
        $userID = $user->UserID;
        //chekeamos que exista el usuario y el post
        (new PersonalData())->checkUsername($username);

        $postID = Crypt::decryptString($encryptID);

        (new UserPost())->checkPostID($postID);
        
        //obtenemos ciertos datos del post
        $post =  UserPost::with([
            //obtenemos su contenido multimedia
            "MultimediaPost",
            //en caso de que el usuario logeado haya likeado o visualizado el post
            "Likes"=>function($queryLike) use ($userID){
                $queryLike->where("NicknameID",$userID);
            },
            "Visualizations"=>function($queryVisualization) use ($userID){
                $queryVisualization->where("NicknameID",$userID);
            },
            //obtenemos tambien datos del usuario que realiazo el posteo
            "User"=>function($queryUser){
                $queryUser->select("UserID","PersonalDataID")
                ->with([
                    "PersonalData"=>function($queryPersonal){
                        $queryPersonal->select("PersonalDataID","Nickname");
                    }
                ]);
            },
            //mostramos todos los comentarios que tuvo el post
            "Comments"=>function($comments) use ($userID){
                $comments
                ->select()
                //por cada comentario mostramos la cantidad de visualizaciones,likes y comentarios que tiene el mismo
                ->withCount([
                    "Visualizations",
                    "Likes",
                    "Comments"
                ])
                ->with([
                    "user"=>function($queryUserComment){
                        $queryUserComment->select("UserID","PersonalDataID")
                        ->with([
                            "PersonalData"=>function($queryPersonalComment){
                                $queryPersonalComment->select("PersonalDataID","Nickname");
                            },
                        ]);
                    },
                    "Likes"=>function($queryLikeComment) use ($userID){
                        $queryLikeComment->where("NicknameID",$userID);
                    },
                    "Visualizations"=>function($queryVisualizationComment) use ($userID){
                        $queryVisualizationComment->where("NicknameID",$userID);
                    },
                    "MultimediaPost"
                ]);
            },
        ])
        ->withCount([
            "Likes",
            "Visualizations",
            "Comments"
        ])
        //obtenemos el nombre del usuario
        ->where("PostID",$postID)
        ->first();

        //por cada comentario establecemos los links para interactuar
        (new Comment())->setLinksInteraction($post->Comments);


        return $this->setLinksInteraction($post);
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
