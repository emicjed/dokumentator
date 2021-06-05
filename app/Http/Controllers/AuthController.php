<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Helpers;
use App\Models\DevicesMac;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $fields = $request->validate([
            'name' => 'required|string',
            'email' => 'required|string|unique:users,email',
            'password' => 'required|string|confirmed'
        ]);

        $mac = substr(exec('getmac'), 0, 17);

        $user = User::create([
            'name' => $fields['name'],
            'email' => $fields['email'],
            'user_token' => Helpers::generateUserToken(),
            'password' => bcrypt($fields['password'])
        ]);
        $device_mac = DevicesMac::create([
            'user_token' => $user->user_token,
            'device_mac' => $mac
        ]);

        MailController::sendRegisterMail($user);

        $response = [
            'message' => 'Plase check your email, and wait for approval by administrator'
        ];

        return response($response, 201);

    }

    public function login(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $fields['email'])->first();

        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'massage' => 'Wrong credentials'
            ]);
        }
        $mac = substr(exec('getmac'), 0, 17);
        $device_mac = DevicesMac::where('device_mac', $mac)->where('user_token', $user->user_token)->first();
        if(!$device_mac){
            MailController::sendAuthorisationDeviceMail($user);
            return response([
                'massage' => 'Send email to authorize new device'
            ]);
        }

        $token = $user->createToken('auth_token')->plainTextToken;

        $response = [
            'token' => $token
        ];

        return response($response, 201);
    }

    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        $response = ['message' => 'Logged out'];
        return response($response, 201);
    }
}
