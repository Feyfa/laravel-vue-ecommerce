<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $validate = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'max:255','email', 'unique:users'],
            'password' => ['required', 'string', 'min:6'],
        ]);
        $validate['password'] = Hash::make($validate['password']);

        User::create($validate);

        return response()->json(['status' => 201, 'message' => 'register success'], 201);
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => ['required', 'string', 'max:255', 'email'],
            'password' => ['required', 'string'],
        ]);

        if(!Auth::attempt($request->only('email', 'password')))
            return response()->json(['status' => 401, 'message' => 'invalid login details'], 401);
        
        $token = $request->user()->createToken('authToken')->plainTextToken;
        $user = User::where('email', $request->email)
                    ->first();

        return response()->json(['status' => 200, 'message' => 'login success', 'token' => $token, 'user' => $user], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->tokens()->delete();

        return response()->json(['status' => 200, 'message' => 'logout success'], 200);
    }
}
