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
        return $this->belongsTo(PostsInteraction::class,"InteractionID");
    }

    public function commentPostView(Request $request,$username){
        try{
            //verificamos que exista el usuario y el post en cuestion
            $user = PersonalData::where("Nickname",$username)->first();
            if(!$request->query("post")){
                throw new Exception();
            }
            $post = UserPost::
            where("PostID",$request->query("post"))
            ->with([
                "MultimediaPost"=>function($queryMultimedia){
                    $queryMultimedia->select("MultimediaID","PostID","Url");
                },
                "User"=>function($query){
                    $query
                    ->select("UserID","PersonalDataID")
                    ->with([
                        "personalData"
                    ]);
                }
            ])
            ->first();

            if(!($user && $post)){
                throw new Exception();
            }

            return view("app.posts.commentPosts",compact("post"));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //guardamos las imagenes y asignamos el contenido al comentario
    public function createMultimediaComment($images,$commentID){
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
            $newMultimedia->CommentID = $commentID;

            $newMultimedia->save();
        }
    }

    //creamos un nuevo comentario
    public function createCommentPost($interactionID,$message){
        $user = Auth::user();

        $userID = $user->UserID;

        $newComment = new Comment();

        $newComment->InteractionID = $interactionID;
        $newComment->NicknameID = $userID;
        $newComment->Message = $message;

        $newComment->save();

        return $newComment->CommentID;
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
        select("PostID","InteractionID")
        ->where("PostID",$request->query("post"))->first();

        if(!$post){
            return response()->json(["errors"=>"No se ha encontrado el post"],404);
        }

        $interactionID = $post->InteractionID;

        //obtenemos el mensaje
        $message = $request->message;

        //creamos y obtenemos el nuevo id del comentario
        $commentID = $this->createCommentPost($interactionID,$message);

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
