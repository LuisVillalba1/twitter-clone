<?php

namespace App\Models;

use Dflydev\DotAccessData\Data;
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
        return $this->belongsTo(UserPost::class,"PostID");
    }

    //creamos una nueva visualizacion
    public function createVisualization($userID,$postID){
        $newVisualization = new Visualization();

        $newVisualization->PostID = $postID;
        $newVisualization->NicknameID = $userID;

        $newVisualization->save();

        return "creado";
    }

    //chekeamos si ya existe la visualizacion
    public function checkVisualization($postID){
        //obtenemos el usuario autenticado junto a su id
        $user = Auth::user();

        $userID = $user->UserID;        

        //buscamos si ya existe una visualizacion de ese post
        $visualization = Visualization::where("PostID",$postID)
                        ->where("NicknameID",$userID)
                        ->first();
        if($visualization){
            return ;
        }
        //en caso de que no exista la creamos
        return $this->createVisualization($userID,$postID);
    }

    //creamos un nueva visualizacion dependiendo del posteo
    public function VisualizationPost(Request $request,$username){
        try{
            $user = Auth::user();
            //obtenemos el post id
            $postID = $request->query("post");
            if(!$postID){
                return response()->json(["errors"=>"No se ha encontrado el post"],404);
            }

            $post= UserPost::where("PostID",$postID)->first();

            //en caso de que exista un post retornamos un error
            if(!$post){
                return response()->json(["errors"=>"No se ha encontrado el post correspondiente"],404);
            }

            return $this->checkVisualization($postID);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }

}
