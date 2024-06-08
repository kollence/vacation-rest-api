<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        try{

            $rolesExist = Role::exists();
            
            if (!$rolesExist) {
                $defaultRole = Role::create([
                    'name' => 'user',
                ]);
            }
            $user = User::create($request->only('name', 'email', 'password'));

            if (isset($defaultRole)) {
                $user->roles()->attach($defaultRole);
            }else{
                $user->roles()->attach([1]);
            }
            // // return $user->load('roles');
            $token = $user->createToken('user-token',['none'])->plainTextToken;
            $user->token = $token;
            return new UserResource($user->load('roles'));
        }catch(\Exception $e){
            return response()->json(['error' => 'Error: registration failed', 'message' => $e->getMessage()]);
        }
    }

    public function login()
    {

    }

    public function logout()
    {
        
    }
}
