<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::create($request->all());

        return response()->json(['status' => 'success', 'data' => $user]);
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email|exists:users,email',
            'password' => 'required|string|min:6'
        ]);

        $user = User::where('email', $request->email)->firstOrFail();

        if ($user && Hash::check($request->password, $user->password)) {
            $user->update(['api_token' => Str::random(40)]);

            return response()->json(['status' => 'success', 'data' => $user]);
        }
    }

    public function logout()
    {
        auth()->user()->update(['api_token' => null]);

        return response()->json(['status' => 'success']);
    }
}
