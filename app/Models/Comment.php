<?php

namespace App\Models;

use App\Http\Requests\Content\MinID;
use App\Http\Requests\UserPost\NewPostRequest;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class Comment extends Model
{
    use HasFactory;

    //obtenemos la interaccion correspondiente

    protected $primaryKey = "CommentID";
    public function Interaction(){
        return $this->belongsTo(UserPost::class,"PostID");
    }

    public function commentPostView(Request $request,$username,$encryptID){
        try{
            //verificamos que exista el usuario y el post en cuestion
            $user = PersonalData::where("Nickname",$username)->first();

            $postID = Crypt::decryptString($encryptID);

            $post = UserPost::
            with([
                "User"=>function($queryUser){
                    $queryUser
                    ->with("PersonalData")
                    ->select("PersonalDataID","UserID");
                },
                "MultimediaPost"
            ])
            ->where("PostID",$postID)
            ->first();

            if(!$user || !$post){
                throw new Exception();
            }

            $post["linkComment"] = route("commentPost",["username"=>$user->Nickname,"encryptID"=>Crypt::encryptString($postID)]);

            return view("app.posts.commentPosts",compact("post"));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //guardamos las imagenes y asignamos el contenido al comentario
    public function createMultimediaComment($images,$postID){
        foreach($images as $image){
            //obtenemos el nombre del archivo
            $imageName = $image->getClientOriginalName();

            //obtenemos el link a donde queremos guardar la imagen
            $imageLink = $image->store("public/images");

            //obtenemos la url de la imagen guardada
            $newUrl = Storage::url($imageLink);

            //creamos el nuevo contenido multimedia
            $newMultimedia = new MultimediaPost();

            $newMultimedia->Name = $imageName;
            $newMultimedia->Url = $newUrl;
            $newMultimedia->PostID = $postID;

            $newMultimedia->save();
        }
    }

    //creamos un nuevo comentario
    public function createCommentPost($postID,$message){
        //obtenemos los datos del usuario
        $user = Auth::user();

        $userID = $user->UserID;

        $newComment = new UserPost();

        $newComment->UserID = $userID;
        $newComment->ParentID = $postID;
        $newComment->message = $message;

        $newComment->save();

        return $newComment->PostID;
    }

    public function commentPost(NewPostRequest $request,$userName,$encryptID){
        try{
        $postID = Crypt::decryptString($encryptID);

        //obtenemos el post junto a su interactionID
        $post = UserPost::
        where("PostID",$postID)->first();

        $user = PersonalData::where("Nickname",$userName)->first();

        if(!$user){
            return response()->json(["errors"=>"No se ha enontrado el usuario correspondiente"],404);
        }

        if(!$post){
            return response()->json(["errors"=>"No se ha encontrado el post"],404);
        }
        //obtenemos el mensaje
        $message = $request->message;

        //creamos y obtenemos el nuevo id del comentario
        $commentID = $this->createCommentPost($post->PostID,$message);

        //obtenemos y verificamos que existan imagenes
        $images = $request->file('images');

        if($images){
            //en caso de que existan imagenes, asignamos el contenido multimedia al comentario
            $this->createMultimediaComment($images,$commentID);
        }
        if($userName != Auth::user()->PersonalData->Nickname){
            //creamos la nueva notificacion para el usuario y se la enviamos
            (new PostsNotification())->createNotificationComment($postID,$user->PersonalDataID,$userName,$commentID,Auth::user()->PersonalData->Nickname);
        }

        return redirect()->route("mainApp")->getTargetUrl();
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al comentar el posteo,por favor intentelo mas tarde."],500);
        }

    }

    //obtenemos los comentarios de un posteo, minPostID lo utilizaremos para cuando se quieran recibir nuevos comentarios
    public function getPostsComments($username,$encryptID,MinID $request){
        try{
            $minPostID = $request->Id;

            //verificamos si existe el posteo y el usuario
            $postID = Crypt::decryptString($encryptID);
            (new UserPost())->checkPostID($postID);
            (new PersonalData())->checkUsername($username);
            
            //obtenemos el id el usuario autenticado
            $userID = Auth::user()->UserID;

            $comments = UserPost::
            select()
            //obtenemos la cantidad de visualizaciones,likes y comentarios
            ->withCount([
                "Visualizations",
                "Likes",
                "Comments"
            ])
            //a su ves informacion del usuario que realizo el comentario
            ->with([
                "user"=>function($queryUserComment){
                    $queryUserComment->select("UserID","PersonalDataID")
                    ->with([
                        "PersonalData"=>function($queryPersonalComment){
                            $queryPersonalComment->select("PersonalDataID","Nickname");
                        },
                        "Profile"=>function($parentProfile){
                            $parentProfile->select("ProfileID","ProfilePhotoURL","ProfilePhotoName");
                        }
                    ]);
                },
                //tambien obtenemos si el usuario autenticado likeo o visualizo el comentario
                "Likes"=>function($queryLikeComment) use ($userID){
                    $queryLikeComment->where("NicknameID",$userID);
                },
                "Visualizations"=>function($queryVisualizationComment) use ($userID){
                    $queryVisualizationComment->where("NicknameID",$userID);
                },
                //obtenemos el contenido multimedia del posteo
                "MultimediaPost"
            ])
            ->where("ParentID",$postID)
            ->where("PostID", '>',$minPostID)
            ->limit(15)
            ->get();

            $this->setLinksInteraction($comments);

            return $comments;
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }

    //por cada comentario obtenemos los links para para interacciones
    public function setLinksInteraction($comments){
        foreach($comments as $comment){
            //obtengo el usuario y encript el postID
            $commentID = Crypt::encryptString($comment->PostID);
            $userName = $comment->user->PersonalData->Nickname;
            $comment["linkLike"] = route("likePost",["username"=>$userName,"encryptID"=>$commentID]);
            $comment["linkVisualization"] = route("VisualizationPost",["username"=>$userName,"encryptID"=>$commentID]);
            $comment["linkComment"] = route("commentPostView",["username"=>$userName,"encryptID"=>$commentID]);
            $comment["linkPost"] = route("showPost",["username"=>$userName,"encryptID"=>$commentID]);
            $comment["linkProfile"] = route("showProfile",["username"=>$userName]);
        } 
    }

}
