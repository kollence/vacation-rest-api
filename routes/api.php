<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\TeamsController;
use App\Http\Controllers\Api\V1\UsersController;
use App\Http\Controllers\Api\V1\VacationsController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);


Route::group(['prefix' => 'v1', 'namespace' => 'App\Http\Controllers\Api\V1','middleware' => 'auth:sanctum'], function(){
    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    // Teams
    Route::post('/teams/remove-team-user/{team}', [TeamsController::class, 'removeTeamUser']);
    Route::apiResource('teams', TeamsController::class);

    Route::apiResource('users', UsersController::class);
    Route::get('/vacation-requests/show-history', [VacationsController::class, 'showHistory']); 
    Route::patch('/vacation-requests/{id}/approve', [VacationsController::class, 'approve']);
    Route::apiResource('vacation-requests', VacationsController::class);
    // Auth logout
    Route::post('/logout', [AuthController::class, 'logout']);
});