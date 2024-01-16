<?php

use App\Http\Controllers\AccessController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegisterController;
use App\Http\Middleware\CheckFirstStepRegistration;
use Illuminate\Support\Facades\Route;
use Laravel\Socialite\Facades\Socialite;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/google-siging', function () {
    return Socialite::driver('google')->redirect();
})->name("google-siging");
 
Route::get('/google-callback', function () {
    $user = Socialite::driver('google')->user();

    return $user->name;
});

Route::controller(AccessController::class)->group(function(){
    Route::get("/","index")->name("main");
    Route::get("/siging","session")->name("siging");
});

Route::controller(RegisterController::class)->group(function(){
    Route::get("/singup","main")->name("singup1step");
    Route::post("/singup","createStep1")->name("singup1create");

    Route::get("/singup/step2","showStep2")->middleware("check_step1_register")->name("singup2step");
    Route::post("/singup/step2","createStep2")->name("singup2create");

    Route::get("/singup/step3","showStep3")->middleware("check_step2_register")->name("singup3step");
    Route::post("/singup/step3","createStep3")->name("singup3create");

    Route::get("/singup/step4","showStep4")->name("singup4step");
});