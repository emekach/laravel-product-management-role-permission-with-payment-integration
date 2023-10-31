<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function viewUser()
    {


        $user = User::all();

        if ($user->isNotEmpty()) {
            return response()->json([
                'users' => $user,
            ]);
        }

        return response()->json([
            'error' => 'No user found'
        ]);
    }

    public function cancelUserAccess($id)
    {


        $user = User::find($id);

        if ($user) {
            $user->status = 0;
            $user->update();

            return response()->json([
                'error' => 'User Access Revoked'
            ]);
        }

        return response()->json([
            'error' => 'No user found'
        ]);
    }

    public function activateUserAccess($id)
    {


        $user = User::find($id);

        if ($user) {
            $user->status = 1;
            $user->update();

            return response()->json([
                'error' => 'User Access Granted'
            ]);
        }

        return response()->json([
            'error' => 'No user found'
        ]);
    }
}
