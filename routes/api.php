<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TeamsController;
use App\Http\Controllers\Api\V1\UsersController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1','middleware' => 'auth:sanctum'], function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
   // Teams
    Route::apiResource('teams', TeamsController::class);
    Route::apiResource('users', UsersController::class);
    // Route::get('/teams', [TeamsController::class, 'index']);
    // Route::get('/teams/{team}', [TeamsController::class, 'show']);
    // Route::post('/teams', [TeamsController::class, 'store']);
    // Route::put('/teams/{team}', [TeamsController::class, 'update']);
    // Route::delete('/teams/{team}', [TeamsController::class, 'destroy']);
    // Auth logout
    Route::post('/logout', [AuthController::class, 'logout']);
});