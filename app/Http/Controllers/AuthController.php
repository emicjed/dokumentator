<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;

class AuthController extends Controller
{
    public function register(Request $request) {
        $fields = $request->validate([
            'name'=> 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'password' => bcrypt($fields['password'])
        ]);


        $auth_token = Helpers::generateAuthToken($user);
        $token = $user->createToken($auth_token)->plainTextToken;

        $response = [
            'user' => $user,
            'token' =>$token
        ];

        return response($response, 201);
    }

    public function login(Request $request) {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if(!$user || !Hash::check($fields['password'], $user->password)){
            return response([
                'massage' => 'Wrong credentials'
            ]);
        }

        $auth_token = Helpers::generateAuthToken($user);
        $token = $user->createToken($auth_token)->plainTextToken;

        $response = [
            'user' => $user,
            'token' =>$token
        ];

        return response($response, 201);
    }

    public function logout(Request $request) {
        $user = $this->auth->user();
        $user->tokens()->delete();
        $response = ['message' => 'Logged out'];
        return response($response, 201);
    }
}
