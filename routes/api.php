<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TeamsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1','middleware' => 'auth:sanctum'], function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::get('/teams', [TeamsController::class, 'index']);
    Route::get('/teams/{id}', [TeamsController::class, 'show']);
    Route::post('/teams', [TeamsController::class, 'store']);
    Route::put('/teams/{id}', [TeamsController::class, 'update']);
    Route::delete('/teams/{id}', [TeamsController::class, 'destroy']);
    // Auth logout
    Route::post('/logout', [AuthController::class, 'logout']);
});