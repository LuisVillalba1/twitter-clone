<?php

namespace App\Http\Controllers;

use App\Http\Requests\Register\Google\NicknameRequest;
use App\Models\PersonalData;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function create($user){
        try{
            //si el usuario existe lo redirigimos a la app main
            $foundUser = User::
            where("Email",$user->email)
            ->with(["PersonalData"])
            ->first();

            if($foundUser){
                Auth::login($foundUser);
                return redirect()->route("mainApp");
            }

            //creamos un un nuevo usuario
            $newUser = new User();

            $newUser->Name = $user->name;
            $newUser->Email = $user->email;

            $newUser->save();

            session()->put("emailGoogle",$newUser->Email);

            //lo redirigimos para crear un nombre de usuario
            return redirect()->route("googleUsername");
        }
        catch(\Exception $e){
            return redirect()->back()->with($e->getMessage());
        }   
    }

    //vista para crear un nombre de usuario con google
    public function showUsernameCreate(){
        return view("google.createUsername");
    }

    //creamos un username
    public function createUsername(NicknameRequest $request){
        try{
            //creamos un nuevo personal data
            $nickname = $request->input("nickname");

            $personalData = new PersonalData();

            $personalData->Nickname = $nickname;

            $personalData->save();

            //buscamos el usuario con el email en concreto
            $user = User::
            where("Email",session()->get("emailGoogle"))
            ->with(["PersonalData"])
            ->first();

            //le asignamos el nuevo personal data
            $user->PersonalDataID = $personalData->PersonalDataID;
            

            $user->save();

            //creamos un nuevo perfil a partir del usuario

            (new Profile())->createProfile($user->UserID);           

            //autenticamos el usuario y limpiamos todos los datos de session
            Auth::login($user);


            session()->flush();

            return route("mainApp");

        }
        catch(\Exception $e){
            return response()->json(["error",$e->getMessage()],500);
        }   
    }
}
