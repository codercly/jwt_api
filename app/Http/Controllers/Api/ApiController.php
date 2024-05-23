<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class ApiController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            "name" => "required|string",
            "email" => "required|string|email|unique:users",
            "password" => "required|confirmed"
        ]);


        User::create([
            "name" => $request->name,
            "email" => $request->email,
            "password" => bcrypt($request->password)
        ]);

        return response()->json([
            "status" => true,
            "message" => "User registered",
        ]);
    }

    public function login(Request $request)
    {
        // Validation
        $request->validate([
            "email" => "required|email",
            "password" => "required"
        ]);

        // $token = auth()->attempt([
        //     "email" => $request->email,
        //     "password" => $request->password
        // ]);

        $token = Auth::attempt([
            "email" => $request->email,
            "password" => $request->password
        ]);

        if (!$token) {

            return response()->json([
                "status" => false,
                "message" => "Invalid login details"
            ]);
        }

        return response()->json([
            "status" => true,
            "message" => "User logged in succcessfully",
            "token" => $token,
            //"expires_in" => auth()->factory()->getTTL() * 60
        ]);
    }


    public function profile()
    {
        $userData = request()->user();

        return response()->json([
            "status" => true,
            "message" => "Profile data",
            "data" => $userData,
        ]);
    }

    public function refreshToken()
    {
        $token = auth()->refresh();
        // $token = Auth::refresh();

        return response()->json([
            "status" => true,
            "message" => "New access token",
            "token" => $token,
        ]);
    }


    public function logout()
    {
        auth()->logout();

        return response()->json([
            "status" => true,
            "message" => "User logged out successfully"
        ]);
    }
}
