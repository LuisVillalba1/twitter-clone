<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

class PostsInteraction extends Model
{
    use HasFactory;
    protected $primaryKey = "InteractionID";


    //obtenemos los likes
    public function Likes(){
        return $this->hasMany(Like::class,"InteractionID");
    }

    //obtenemos los comentarios
    public function Comments(){
        return $this->hasMany(Comment::class,"InteractionID");
    }

    public function Visualizations(){
        return $this->hasMany(Visualization::class,"InteractionID");
    }

    public function UserPost(){
        return $this->hasOne(UserPost::class,"PostID");
    }

    //creamos una nueva interaccion
    public function createInteraction(){
        $newInteraction = new PostsInteraction();

        $newInteraction->save();

        return $newInteraction->InteractionID;
    }

    public function getPublicInteractions(){
        //obtenemos el UserID el cual equivale a lo mismo que el personalDataID
        $userID = Auth::user()->UserID;

        //buscamos todas las interacciones
        $interactions = PostsInteraction::
            select("InteractionID")
            //mostramos la cantidad de likes, comentarios y visualizaciones que contiene el post
            ->withCount("Likes","Comments","Visualizations")
            ->with([ 
                //verificamos si el post esta likeado por el usuario authenticado
                "Likes" => function ($queryLikes) use ($userID){
                    $queryLikes->where('NicknameID', $userID);
                },
                //verificamos si el post esta comentado por el mismo usuario
                "Comments"=>function($queryComments) use ($userID){
                    $queryComments->where("NicknameID",$userID);
                },
                //verificiamos si el post esta visualizado por el mismo usuario
                "Visualizations"=>function($queryVisualizations) use ($userID){
                    $queryVisualizations->where("NicknameID",$userID);
                },
                //obtenemos el post
                "UserPost"=>function($query3){
                    $query3->select("PostID","Message","UserID")
                    ->with([
                        //de dicho post obtenemos los datos del usuario como el nombre
                        "User"=>function($query4){
                            $query4->select("UserID","PersonalDataID","Name")
                            ->with([
                                //y obtenemos su nickname
                                "personalData"=>function($query5){
                                    $query5->select("PersonalDataID","Nickname");
                                }
                            ]);
                        },
                        //mostramos en caso de que contenga las imagenes del posteo
                        "MultimediaPost"=>function($query6){
                            $query6->select("PostID","Name","Url");
                        },
                    ]);
                },
            ])->get();
        
        
        foreach($interactions as $interaction){
            $nickname = $interaction->UserPost->User->PersonalData->Nickname;
            $idEncrypt = Crypt::encryptString($interaction->UserPost->PostID);
            $interaction->linkPost = route("showPost",["username"=>$nickname,"encryptID"=>$idEncrypt]);
            $interaction->linkLike = route("likePost",["username"=>$nickname,"encryptID"=>$idEncrypt]);
        }

        return $interactions;
    }

    public function getPostInteracction($id){
        return PostsInteraction::where("InteractionID",$id)->first();
    }
}
