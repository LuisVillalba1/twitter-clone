<?php

namespace App\Http\Controllers;

use App\Http\Requests\Content\MinID;
use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\settings\EditProfileRequest;
use App\Models\Follow;
use App\Models\Like;
use App\Models\PersonalData;
use App\Models\Profile;
use App\Models\User;
use App\Models\UserPost;
use Barryvdh\Debugbar\Facades\Debugbar;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }
    
    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }

    //permitimos al usuario editar su perfil
    public function editProfile(EditProfileRequest $request){
        try{
            //obtenemos el perfil del usuario
            $profile = Profile::where("UserID",Auth::user()->UserID)->first();

            if(!$profile){
                throw new Exception("Perfil no encontrado");
            }

            //en caso de existir modificamos el nombre de usuario
            if($request->name){
                $userData = User::where("UserID",Auth::user()->UserID)->first();

                $userData->Name = $request->name;

                $userData->save();

                //actualizamos el usuario en la session
                Auth::setUser($userData);
            }

            //modificamos el perfil
            (new Profile())->modifyProfile($profile,$request);

            return redirect()->route("showProfile",["username"=>Auth::user()->PersonalData->Nickname])->getTargetUrl();
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()],500);
        }
    }

    //mostramos el perfil del usuario
    public function showProfile($username,Request $request){
        try{
            return (new Profile())->showProfile($username,"profile",$request);
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //mostramos para poder editar el perfil del usuario
    public function showEditProfile(){
        try{
            $user = Auth::user();

            //obtenemos el nickname del usuario
            $personalData = PersonalData::
            where("PersonalDataID",$user->UserID)
            ->select("PersonalDataID","Nickname")
            ->first();
            
            //los datos del perfil en caso de que existan
            $profileData = Profile::where("ProfileID",$user->UserID)
            ->select("Biography","CoverPhotoURL","ProfilePhotoURL","CoverPhotoName","ProfilePhotoName")
            ->first();
    
            $username = $personalData->Nickname;

            //obtenemos el nombre
            $name = $user->Name;
    
            return view("app.settings.settingProfile",compact(["username","name","profileData"]));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //obtenemos los post del usuario autenticado
    public function getUserPosts($username){
        try{
            //verificamos primero que exista el usuario
            $personalData = (new PersonalData())->checkUsername($username);
            return (new User())->getUserPosts($username,$personalData->PersonalDataID);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al cargar los posteos del usuario"],500);
        }
    }

    //mostramos la vista de respuestas
    public function showAnswersUser($username,Request $request){
        try{
            return (New Profile())->showProfile($username,"answers",$request);
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //obtenemos todo aquel posteo que haya sido una respuesta a otro
    public function getAnswersUser($username){
        try{
            //verificamos que exista el usuario y obtenemos las respuestas de este
            $personalData = (new PersonalData())->checkUsername($username);
            return (new Profile())->getAnswers($username,$personalData->PersonalDataID);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al obtener las respuestas"],500);
        }
    }

    //mostramos la vista de los megustas de cierto usuario
    public function showLikesUser($username,Request $request){
        try{
            return (new Profile())->showProfile($username,"likes",$request);
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //obtenemos los posteos likeados
    public function getLikesUser($username){
        try{
            $personalData = (new PersonalData())->checkUsername($username);
            return (new Like())->getLikesPosts($username,$personalData->PersonalDataID);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al obtener los posteos likeado"],500);
        }
    }

    //mostramos la vista de los seguidores de tal usuario
    public function showUserFollows($username){
        try{
            $profile = (new PersonalData())->checkUsername($username);
            $nickname = $profile->Nickname;
            return view("app.profile.follow.follows",compact("nickname"));
        }
        catch(\Exception $e){
            $errorData = json_encode($e->getMessage(),true);
            return redirect()->route("errorPage");
        }
    }

    //obtenemos los seguidos de un usuario
    public function getUserFollows($username){
        try{
            $profile = (new PersonalData())->checkUsername($username);
            return (new Follow())->getFollows($profile->PersonalDataID);
        }
        catch(\Exception $e){
            return response()->json(["errors" => "Ha ocurrido un error al obtener los seguidos del usuario"], 404);
        }
        
    }

    //mostramos la vista de followers
    public function showUserFollowers($username){
        try{
            $profile = (new PersonalData())->checkUsername($username);
            $nickname = $profile->Nickname;
            return view("app.profile.follow.followers",compact("nickname"));
        }
        catch(\Exception $e){
            return redirect()->route("errorPage");
        }
    }

    //obtenemos los seguidores de un usuario
    public function getUserFollowers($username){
        try{     
            $profile = (new PersonalData())->checkUsername($username);
            return (new Follow())->getFollowers($profile->PersonalDataID);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>"Ha ocurrido un error al obtener los seguidores del usuario"],500);
        }
    }
}
