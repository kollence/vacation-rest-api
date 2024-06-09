<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\UserResource;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function register(RegisterUserRequest $request)
    {
        $data = $request->validated(); // Already validated by RegisterUserRequest

        // $defaultRole = config('auth.default_role'); // Use configuration for role name
        $defaultRole = 'user';
        try {
            // Attempt to create the role if it doesn't exist
            if (!Role::where('name', $defaultRole)->exists()) {
                Role::create([
                    'name' => $defaultRole,
                ]);
            }
    
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => $data['password'],
            ]);
            $token = $user->createToken("{$defaultRole}-token")->plainTextToken;
            
            $user->token = $token;
    
            $user->roles()->attach(Role::where('name', $defaultRole)->firstOrFail());
    
            return response()->json([
                'message' => 'User created successfully',
                'user' => new UserResource($user),
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function login(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'email' => 'required|email',
                'password' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $credentials = $validator->validated();

            if (!Auth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid email or password'], 401);
            }

            $user = Auth::user();

            $token = $user->createToken("user-token")->plainTextToken;
                
            $user->token = $token;

            return response()->json([
                'message' => 'Login successful',
                'user' => new UserResource($user->load('roles')),
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->delete();

            return response()->json(['message' => 'Logged out']);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
