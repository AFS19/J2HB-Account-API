<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\RegisterRequest;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    /* Register method */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => ['required', 'string', 'between:2,100'],
            'phone' => ['required', 'string', 'min:10', 'regex:/^(06|07)\d{8}$/'],
            'email' => ['required', 'string', 'email', 'max:100', 'unique:users'],
            'password' => ['required', 'string', 'confirmed', 'min:6'],
            'city' => ['required', 'string'],
            'reason' =>  ['required', 'string'],
        ]);
        if ($validator->fails()) {
            return Helper::handleValidationErrors($validator);
        }
        try {
            # data save & create new user
            // $user = User::create($request->validated());
            $user = new User;
            $user->setAttributes($validator->validated());
            $role = Role::where('name', 'candidate')->first();
            $user->addRole($role);
            $user->save();
            # assign candidate role
            // $user->addRole('candidate');
            // $user->setMeta('city', "test city");
            // $user->setMeta('reason', "test reason");
            // $user->saveMeta();

            # return success response
            return Helper::handleSuccessMessage("user created successfully");
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
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
            return Helper::handleExceptions($th);
        }
    }

    /* Refresh token method */
    public function refreshToken()
    {
        try {
            return $this->responseWithToken(Auth::refresh(), Auth::user()->only('name', 'email'), "new access token generated successfully");
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
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
            return Helper::handleExceptions($th);
        }
    }

    /* Logout method */
    public function logout()
    {
        try {
            Auth::logout();
            return Helper::handleSuccessMessage("Logged out successfully");
        } catch (\Throwable $th) {
            return Helper::handleExceptions($th);
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
