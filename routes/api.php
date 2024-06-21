<?php

use App\Http\Controllers\Auth\RegisterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\ClassesController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// auth
Route::post('/register', RegisterController::class);
Route::post('/login', LoginController::class );
Route::post('/logout', LogoutController::class)->middleware('auth:sanctum');

//Route::get('/getClass', [ClassesController::class , 'index']);

Route::apiResource('/Class', ClassesController::class)->middleware('auth:sanctum');
