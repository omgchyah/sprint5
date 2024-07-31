<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ApiController;

//Open routes
Route::post("register", [ApiController::class, "register"]);
Route::post("login", [ApiController::class, "login"]);

//Protected Routes

/* Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:api'); */


