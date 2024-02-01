<?php

namespace App\Models;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Like extends Model
{
    use HasFactory;

    //obtenemos la interaccion correspondiente
    public function Interaction(){
        return $this->belongsTo(PostsInteraction::class,"InteractionID");
    }

    public function checkLike($interactionID,$personalDataID){
        //if no se ha likeado creamos un nuevo like
        $like = Like::where("InteractionID",$interactionID)->first();

        if(!$like){
            $newLike = new Like();

            $newLike->InteractionID = $interactionID;
            $newLike->NicknameID = $personalDataID;

            return $newLike->save();
        }

        return "ya esta likeado el post";
    }

    public function likePost(Request $request,$username){
        try{
            //obtenemos el post al que se le dio like
            $postID = $request->query("post");

            if(!$postID){
               throw new Exception("Query no encontrada");
            }
            
            //obtenemos la interaccion
            $interaction = (new PostsInteraction())->getPostInteracction($postID);
            
            //obtenemos los datos de autenticacion del usuario
            $user = Auth::user();
            
            $personalData = PersonalData::select("PersonalDataID")->where("PersonalDataID",$user->UserID)->first();            

            $personalDataID = $personalData->PersonalDataID;
            
            
            return $this->checkLike($interaction->InteractionID,$personalDataID);

        }
        catch(\Exception $e){
            return response()->json(["error"=>$e->getMessage()],404);
        }
    }
}
