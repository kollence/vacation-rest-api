<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterUserRequest;
use App\Http\Resources\ApiUserResource;
use App\Models\Role;
use App\Models\User;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class AuthController extends Controller
{
    public function __construct(
        protected UserService $userService
    ) {
    }


    public function register(RegisterUserRequest $request)
    {

        $user = $this->userService->create($request->toArray());

        $token = $user->createToken("user-token")->plainTextToken;

        $user->token = $token;

        return response()->json([
            'user' => new ApiUserResource($user),
        ], 201);
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
                'user' => new ApiUserResource($user),
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
