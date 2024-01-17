<?php

namespace App\Http\Controllers;

use App\Http\Requests\Register\Google\NicknameRequest;
use App\Models\PersonalData;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
    public function create($user){
        try{
            //If the user has exist,redirect from the main app
            $foundUser = User::where("Email",$user->email)->first();

            if($foundUser){
                Auth::login($foundUser);
                return redirect()->route("mainApp");
            }

            //create user and redirect main app
            $newUser = new User();

            $newUser->Name = $user->name;
            $newUser->Email = $user->email;

            $newUser->save();

            session()->put("emailGoogle",$newUser->Email);

            return redirect()->route("googleUsername");
        }
        catch(\Exception $e){
            return redirect()->back()->with($e->getMessage());
        }   
    }

    public function showUsernameCreate(){
        return view("google.createUsername");
    }

    public function createUsername(NicknameRequest $request){
        try{
            //create a new personal data
            $nickname = $request->input("nickname");

            $personalData = new PersonalData();

            $personalData->Nickname = $nickname;

            $personalData->save();

            //asing to the user personal data
            $user = User::where("Email",session()->get("emailGoogle"))->first();

            $user->PersonalDataID = $personalData->PersonalDataID;

            $user->save();

            //Authenticate user
            Auth::login($user);

            session()->flush();

            return route("mainApp");

        }
        catch(\Exception $e){
            return response()->json(["error",$e->getMessage()],500);
        }   
    }
}
