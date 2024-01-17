<?php

namespace App\Http\Controllers;

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

            session()->put("user",$newUser);

            return redirect()->route("googleUsername");
        }
        catch(\Exception $e){
            return redirect()->back()->with($e->getMessage());
        }   
    }

    public function showUsernameCreate(){
        return view("google.createUsername");
    }

    public function createUsername(Request $request){
        return $request;
    }
}
