<?php

namespace App\Models;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class Like extends Model
{
    use HasFactory;

    //obtenemos la interaccion correspondiente
    public function Post(){
        return $this->belongsTo(UserPost::class,"PostID");
    }

    //verificaremos si el posteo se ha likeado o no
    public function checkLike($postID,$AuthpersonalDataID,$nickname,$userID){
        //verificamos si el usuario le ha dado like al post correspondiente
        $like = Like::where("PostID",$postID)
                ->where("NicknameID",$AuthpersonalDataID)
                ->first();

        //en caso de que no haya likeado al post creamos un nuevo like
        if(!$like){
            $newLike = new Like();

            $newLike->PostID = $postID;
            $newLike->NicknameID = $AuthpersonalDataID;

            $newLike->save();

            //creamos una notificacion el para el propietario del posteo
            if(Auth::user()->PersonalData->Nickname != $nickname){
                (new PostsNotification())->createNotificationLike($postID,$userID,$nickname);
            }
            return true;
        }

        //si no eliminamos el like en concreto
        Like::where("PostID",$postID)
        ->where("NicknameID",$AuthpersonalDataID)
        ->delete();

        return false;
    }

    public function likePost(Request $request,$username,$idEncrypt){
        try{
            //verificamos si existe el usuario propietario del posteo, y obtenemos su id
            $userPostPersonalData = (new PersonalData())->checkUsername($username);
            $userPostID = $userPostPersonalData->PersonalDataID;

            $postID = Crypt::decryptString($idEncrypt);

            (new UserPost())->checkPostID($postID);

            //obtenemos los datos del usuario autenticado
            $user = Auth::user();       
            //obtenemos su Personal data como su nombre de usuario
            $personalDataID = $user->PersonalData->PersonalDataID;
            
            //verificamos si hay que likear o dislikear el posteo y a su ves creamos una notificacion para el propietario del posteo
            return $this->checkLike($postID,$personalDataID,$username,$userPostID);

        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }

    //obtenemos todos los posts likeados por un usuario
    public function getLikesPosts($username){
        $user = Auth::user();

        $userID = $user->UserID;

        //obtenemos el usuario al que se quiere obtener los posteos
        $personalData = PersonalData::where("Nickname",$username)->first();


        $likes = Like::
        select("LikeID", "PostID", "NicknameID")
        ->with([
            "Post" => function ($queryPost) use ($userID) {
                $queryPost->with([
                    "MultimediaPost",
                    "Likes" => function ($queryLike) use ($userID) {
                        $queryLike->where("NicknameID", $userID);
                    },
                    "Visualizations" => function ($queryVisualization) use ($userID) {
                        $queryVisualization->where("NicknameID", $userID);
                    },
                    "User" => function ($queryUser) {
                        $queryUser
                            ->select("UserID", "PersonalDataID")
                            ->with([
                                "PersonalData" => function ($queryPersonal) {
                                    $queryPersonal->select("PersonalDataID", "Nickname");
                                },
                                "Profile" => function ($queryProfile) {
                                    $queryProfile->select("ProfileID", "UserID", "ProfilePhotoURL", "ProfilePhotoName");
                                }
                            ]);
                    },
                    "Comments" => function ($queryComments) use ($userID) {
                        $queryComments->where("UserID", $userID);
                    },
                ])
                    ->withCount([
                        "Likes",
                        "Visualizations",
                        "Comments"
                    ]);
            }
        ])
        ->where("NicknameID", $personalData->PersonalDataID)
        ->orderBy("LikeID","desc")
        ->simplePaginate(15);
        
        foreach($likes as $post){
             //mostramos los links para poder interactuar con cada post
             $post = $post->Post;
             $userName = $post->User->PersonalData->Nickname;
             $idEncrypt = Crypt::encryptString($post->PostID);
             $post["linkLike"] = route("likePost",["username"=>$userName,"encryptID"=>$idEncrypt]) ?? null;
             $post["linkComment"] = route("commentPostView",["username"=>$userName,"encryptID"=>$idEncrypt]);
             $post["linkPost"] = route("showPost",["username"=>$userName,"encryptID"=>$idEncrypt]);
             $post["linkProfile"] = route("showProfile",["username"=>$username]);
        }
        
        return $likes;
    }
}
