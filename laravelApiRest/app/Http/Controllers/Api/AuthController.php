<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class AuthController extends Controller
{
    public function register(Request $request)
    {

        $registerData = $request->validate([
            'nickname' => ['required', 'string', 'max:255', 'unique:users,nickname'],
            'name' => ['nullable', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'string', 'min:8'],
        ]);

        Log::info('Register data:', $registerData);

        $registerData['role'] = empty($registerData['name']) ? 'guest' : 'user';
        if (empty($registerData['name'])) {
            $registerData['name'] = 'Anonymous';
        }

        $registerData['password'] = Hash::make($registerData['password']);

        try {
            $user = User::create($registerData);
            $accessToken = $user->createToken('authToken')->accessToken;

            return response()->json([
                'user' => $user,
                'access_token' => $accessToken,
            ], 201);
        } catch (\Exception $e) {
            // Handle the exception, log it, or return an error response
            Log::error('Error creating user:', ['exception' => $e]);
            return response()->json(['message' => 'Error creating user'], 500);
        }


    }

    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string', 'min:8'],
        ]);
    
        if (!auth()->attempt($loginData)) {
            return response()->json(['message' => 'Invalid credentials'], 401);
        }

        try {
            $user = auth()->user();
            $accessToken = $user->createToken('authToken')->accessToken;
        
            return response()->json([
                'user' => $user,
                'access_token' => $accessToken,
            ], 200);
        } catch (ModelNotFoundException $e) {
            Log::error('User not found:', ['exception' => $e, 'user_id' => $userId]);
            return response()->json(['message' => 'User not found'], 404);
        } catch (\Exception $e) {
        Log::error('Error generating access token:', ['exception' => $e]);
        return response()->json(['message' => 'Error generating access token'], 500);
        }
    }
}



