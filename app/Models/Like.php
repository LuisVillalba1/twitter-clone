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
        //verificamos si el usuario le ha dado like al post correspondiente
        $like = Like::where("InteractionID",$interactionID)
                ->where("NicknameID",$personalDataID)
                ->first();

        //en caso de que no haya likeado al post creamos un nuevo like
        if(!$like){
            $newLike = new Like();

            $newLike->InteractionID = $interactionID;
            $newLike->NicknameID = $personalDataID;

            $newLike->save();

            return true;
        }

        //si no eliminamos el like en concreto
        Like::where("InteractionID",$interactionID)
        ->where("NicknameID",$personalDataID)
        ->delete();

        return false;
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
