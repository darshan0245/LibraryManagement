<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiAuthController extends Controller
{
    public function register(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:50',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:8',
        ]);

        $data['password'] = bcrypt($request->password);
        $user = User::create($data);
        $token = $user->createToken('API token')->accessToken;
        return response(['user' => $user, 'token' => $token]);
    }

    public function login(Request $request)
    {
        $data = $request->validate([
            'email' => 'email|required',
            'password' => 'required',
        ]);

        if (!auth()->attempt($data)) {
            return response(['error' => 'Incorrect details'], 401);
        }

        $token = auth()->user()->createToken('api token')->accessToken;

        return response(['user' => auth()->user(), 'token' => $token]);
    }

    public function logOut()
    {
        auth()->user()->token()->revoke();
        return response()->json([
            'message' => 'Sucessfully logged out'
        ]);
    }
}
