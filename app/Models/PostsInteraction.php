<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

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
            with([
                //le sumamos los likes que contiene
                "Likes"=>function($query){
                    $query->select();
                },
                //los comentarios que contiene
                "Comments"=>function($query2){
                    $query2->select();
                },
                //a que usuario le corresponde el post
                "UserPost"=>function($query3){
                    $query3->select("PostID","Message","UserID")
                    ->with([
                        "User"=>function($query4){
                            $query4->select("UserID","PersonalDataID","Name")
                            ->with([
                                "personalData"=>function($query5){
                                    $query5->select("PersonalDataID","Nickname");
                                }
                            ]);
                        },
                        //mostramos en caso de que contenga las imagenes del post
                        "MultimediaPost"=>function($query6){
                            $query6->select("PostID","Name","Url");
                        }
                    ]);
                }
            ])->select("InteractionID")->get();
         
        //en caso de que el usuario authenticado haya dado like al post lo agregamos
        foreach($interactions as $interaction){
            $interaction->liked = $interaction->likes->contains("NicknameID",$userID);
        }    

        return $interactions;
    }

    public function getPostInteracction($id){
        return PostsInteraction::where("InteractionID",$id)->first();
    }
}
