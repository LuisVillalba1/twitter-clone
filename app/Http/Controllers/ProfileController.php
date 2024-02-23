<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Http\Requests\settings\EditProfileRequest;
use App\Models\PersonalData;
use App\Models\Profile;
use App\Models\User;
use Exception;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
            }

            //modificamos el perfil
            (new Profile())->modifyProfile($profile,$request);

            return response()->json(["response"=>"Se ha modificado los datos correctamente"]);
        }
        catch(\Exception $e){
            return response()->json(["errors"=>$e->getMessage()]);
        }
    }
}
