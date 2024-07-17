<?php

use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['namespace' => 'App\Http\Controllers'], function(){
    Route::get('/players', [UserController::class, 'index']);

    Route::get('/players/{id}/games', [UserController::class, 'show']);

});
