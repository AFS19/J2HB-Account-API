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
            User::create([
                "name" => $request->name,
                "tel" => $request->tel,
                "email" => $request->email,
                "password" => bcrypt($request->password),
            ]);

            # return success response
            return response()->json([
                "status" => "success",
                "message" => "user created successfully"
            ]);
        } catch (\Throwable $th) {

            return response()->json([
                "status" => "failed",
                "error" => $th->getMessage(),
            ], 500);
        }
    }

    /* Login method */
    public function login(LoginRequest $request)
    {
        # jwt auth and attempt
        $credentials = $request->only('email', 'password');
        if (!$token = JWTAuth::attempt($credentials)) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        #response
        return $this->responseWithToken($token, $credentials, "logged in successfully");
    }

    /* Refresh token method */
    public function refreshToken()
    {
        return $this->responseWithToken(Auth::refresh(), Auth::user()->only('name', 'email'), "new access token generated successfully");
    }

    /* Logout method */
    public function logout()
    {
        Auth::logout();
        return response()->json([
            "status" => "success",
            'message' => "Logged out successfully",
        ]);
    }

    /* Register method */
    public function responseWithToken($user, $token)
    {
        return response()->json([
            'status' => 'success',
            'user' => $user,
            'access_token' => $token,
            'type' => 'bearer'
        ]);
    }
}