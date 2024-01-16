<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /* Register method */
    public function register(RegisterRequest $request)
    {
        try {
            # data save & create new user
            $user = User::create([
                "name" => $request->name,
                "phone" => $request->phone,
                "email" => $request->email,
                "password" => bcrypt($request->password),
            ]);
            # har code to assign condidat role
            $user->addRole('condidat');

            # return success response
            return response()->json([
                "status" => "success",
                "message" => "user created successfully"
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    /* Login method */
    public function login(LoginRequest $request)
    {
        try {
            # jwt auth and attempt
            $credentials = $request->only('email', 'password');
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
            #response
            return $this->responseWithToken($token, $credentials, "logged in successfully");
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    /* Refresh token method */
    public function refreshToken()
    {
        try {
            return $this->responseWithToken(Auth::refresh(), Auth::user()->only('name', 'email'), "new access token generated successfully");
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    # use profile method
    public function profile()
    {
        try {
            $user = auth()->user();
            $credentials = [
                "name" => $user?->name,
                "email" => $user?->email,
            ];
            return response()->json([
                "message" => "Profile data",
                "user" => $credentials,
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'server error',
                'error' => $th->getMessage(),
            ], 500);
        }
    }

    /* Logout method */
    public function logout()
    {
        try {
            Auth::logout();
            return response()->json([
                "status" => "success",
                'message' => "Logged out successfully",
            ]);
        } catch (\Throwable $th) {
            return response()->json([
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    /* Register method */
    protected function responseWithToken($token, $user, $message)
    {
        return response()->json([
            "message" => $message,
            "user" => $user,
            "access_token" => $token,
            "token_type" => 'bearer',
        ]);
    }
}
