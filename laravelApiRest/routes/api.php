<?php

use App\Http\Controllers\GameController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['namespace' => 'App\Http\Controllers'], function(){

    //GET /players: retorna el llistat de tots els jugadors/es del sistema amb el seu percentatge mitjà d’èxits
    Route::get('/players', [UserController::class, 'index']);

    //GET /players/{id}/games: retorna el llistat de jugades per un jugador/a.
    Route::get('/players/{id}/games', [UserController::class, 'show']);

    //retorna el rànquing mitjà de tots els jugadors/es del sistema. És a dir, el percentatge mitjà d’èxits.
    Route::get('/players/ranking', [UserController::class, 'averageSuccessRanking']);

    //retorna el jugador/a amb pitjor percentatge d’èxit.
    Route::get('players/ranking/loser', [UserController::class, 'showLoser']);

    //GET /players/ranking/winner: retorna el jugador/a amb millor percentatge d’èxit.
    Route::get('/players/ranking/winner', [UserController::class, 'showWinner']);

    //crea un jugador/a.
    Route::post('/players', [UserController::class, 'store']);

    //PUT /players/{id} : modifica todos los atributos
    Route::put('/players/{id}', [UserController::class, 'update']);

    //PATCH /players/{id} : modifica el nombre
    Route::patch('/players/{id}', [UserController::class, 'update']);

    //POST /players/{id}/games/ : un jugador/a específic realitza una tirada dels daus.
    Route::post('/players/{id}/games', [GameController::class, 'store']);

    //DELETE /players/{id}/games: elimina les tirades del jugador/a.
    Route::delete('players/{id}/games', [Gamecontroller::class, 'destroy']);

});
