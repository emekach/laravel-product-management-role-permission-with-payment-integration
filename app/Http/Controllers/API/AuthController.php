<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{


    public function register(Request $request)
    {

        $data = $request->validate([
            'name' => 'required|max:191',
            'email' => 'required|email|max:191|unique:users,email',
            'password' => 'required|string',
            'confirm_password' => 'required|string|same:password'
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password'])
        ]);

        $token = $user->createToken('urbanCubeToken')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token
        ];

        return response()->json($response, 201);
    } // End Method

    public function logout()
    {
        Auth::user()->tokens->each(function ($token, $key) {
            $token->delete();
        });

        return response([
            'message' => 'Logged out successfully'
        ]);
    }


    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'required|email|max:191',
            'password' => 'required|string'
        ]);

        $user = User::where('email', $data['email'])->where('status', 1)->first();

        if (!$user || !Hash::check($data['password'], $user->password)) {
            return response()->json(['message' => 'Invalid Credentials'], 401);
        }

        // Check the user's role(s)
        $roles = $user->getRoleNames()->toArray();

        // You can now perform actions based on the user's role(s)
        if (in_array('admin', $roles)) {
            // User is an Admin
            $roleMessage = 'Admin';
            // Add more actions specific to Admins if needed
        } elseif (in_array('b2c', $roles)) {
            // User is a B2C customer
            $roleMessage = 'B2C Customer';
            // Add more actions specific to B2C customers if needed
        } elseif (in_array('b2b', $roles)) {
            // User is a B2B customer
            $roleMessage = 'B2B Customer';
            // Add more actions specific to B2B customers if needed
        } else {
            // Handle other roles or scenarios
            $roleMessage = 'Role Not Defined';
        }

        $token = $user->createToken('Highwater')->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
            'role' => $roleMessage,
        ];

        return response()->json($response, 200);
    }
}
