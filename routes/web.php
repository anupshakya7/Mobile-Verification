<?php

use App\Http\Controllers\Auth\AuthOTPControler;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/home',[HomeController::class,'index'])->name('home');

Route::controller(AuthOTPControler::class)->group(function(){
    Route::get('/otp/login','login')->name('otp.login');
    Route::post('/otp/generate','generate')->name('otp.generate');

    Route::get('/otp/verification/{user_id}','verification')->name('otp.verification');
    Route::post('/otp/login','loginWithOTP')->name('otp.getLogin');
});
