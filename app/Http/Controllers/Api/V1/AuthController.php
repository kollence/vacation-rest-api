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
        $initialUser = 'user';
        try{
            // if user role doesn't exist
            if (!Role::exists($initialUser)) {
                $defaultRole = Role::create([
                    'name' => $initialUser,
                ]);
            }
            $user = User::create($request->only('name', 'email', 'password'));

            if (isset($defaultRole)) {
                $user->roles()->attach($defaultRole);
            }else{
                $roleId = Role::where('name', $initialUser)->first()->id;
                $user->roles()->attach($roleId);
            }
            $token = $user->createToken('user-token')->plainTextToken;
            
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
