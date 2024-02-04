<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class Visualization extends Model
{
    use HasFactory;

    protected $primayKey = "VisualizationID";

    public function PostInteraction(){
        return $this->belongsTo(PostsInteraction::class,"InteractionID");
    }

    //creamos una nueva visualizacion
    public function createVisualization($userID,$interactionID){
        $newVisualization = new Visualization();

        $newVisualization->InteractionID = $interactionID;
        $newVisualization->NicknameID = $userID;

        $newVisualization->save();

        return "creado";
    }

    //chekeamos si ya existe la visualizacion
    public function checkVisualization($data){
        //obtenemos la interaccion y el usuario
        $interactionID = $data->user->userPosts[0]->InteractionID;
        $usernameID = $data->PersonalDataID;

        //obtenemos el usuario autenticado junto a su id
        $user = Auth::user();

        $userID = $user->UserID;        

        //buscamos si ya existe una visualizacion de ese post
        $visualization = Visualization::where("InteractionID",$interactionID)
                        ->where("NicknameID",$usernameID)
                        ->first();
        if($visualization){
            return ;
        }
        //en caso de que no exista la creamos
        return $this->createVisualization($userID,$interactionID);
    }

    //creamos un nueva visualizacion dependiendo del posteo
    public function VisualizationPost(Request $request,$username){
        try{
            //obtenemos el post id
            $postID = $request->query("post");
            if(!$postID){
                return response()->json(["errors"=>"No se ha encontrado el post"],404);
            }

            $data = PersonalData::with([
                //obtenemos el usuario
                "user"=>function ($query) use ($postID){
                    $query->with([
                        //obtenemos todos los posts
                        "userPosts"=>function($query2) use ($postID){
                            $query2
                            ->select("UserID","Message","InteractionID")
                            ->where("PostID",$postID);
                        }
                    ])->select("PersonalDataID","UserID");
                }
            ])
            //en caso de que el nickname y el post id sean correctos
            ->where("Nickname",$username)
            ->select("Nickname","PersonalDataID")->first();


            //en caso de que no se haya encontrado el post correspondiente
            if(!$data || count($data->user->userPosts) <= 0){
                return response()->json(["errors"=>"No se ha encontrado la publicacion en concreto"],404);
            }
            return $this->checkVisualization($data);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }

}
