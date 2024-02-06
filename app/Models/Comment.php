<?php

namespace App\Models;

use App\Http\Requests\UserPost\NewPostRequest;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class Comment extends Model
{
    use HasFactory;

    //obtenemos la interaccion correspondiente

    protected $primaryKey = "CommentID";
    public function Interaction(){
        return $this->belongsTo(UserPost::class,"PostID");
    }

    public function commentPostView(Request $request,$username){
        try{
            //verificamos que exista el usuario y el post en cuestion
            $user = PersonalData::where("Nickname",$username)->first();
            if(!$request->query("post")){
                throw new Exception();
            }
            if(!$user){
                throw new Exception();
            }

            $post = UserPost::where("PostID",$request->query("post"))->first();
            if(!$post){
                throw new Exception();
            }

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
        $newComment->message->$message;

        $newComment->save();

        return $newComment->PostID;
    }

    public function commentPost(NewPostRequest $request,$userName){
        try{
            //en caso de que no se encuentre el usuario o el parametro query retornamos un error
        if(!$userName){
            return response()->json(["errors"=>"No se ha encontrado el usuario"],404);
        }
        if(!$request->query("post")){
            return response()->json(["errors"=>"No se ha encontrado el post en cuestion"],404);
        }

        //obtenemos el post junto a su interactionID
        $post = UserPost::
        select("PostID")
        ->where("PostID",$request->query("post"))->first();

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

            return redirect()->route("mainApp")->getTargetUrl();
        }

        return redirect()->route("mainApp")->getTargetUrl();
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }

    }
}
