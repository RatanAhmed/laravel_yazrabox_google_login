<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\GoogleController;



Auth::routes();
Route::get('auth/google', [GoogleController::class, 'signInwithGoogle']);
Route::get('auth/google/callback', [GoogleController::class, 'callbackToGoogle']);

Route::group(['middleware' => 'auth'],function(){
    Route::get('/', function () {
        return redirect()->route('home');
    });
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    Route::resource('users', UsersController::class);
    Route::get('/datatable', [UsersController::class, 'getData'])->name('getData');
});