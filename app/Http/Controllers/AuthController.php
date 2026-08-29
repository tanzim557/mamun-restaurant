<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();

        if ($user && ($user->password === $password || Hash::check($password, $user->password))) {
            session(['admin_logged_in' => true, 'admin_user' => $user->toArray()]);
            return response()->json(['success' => true, 'user' => $user]);
        }

        return response()->json(['success' => false, 'error' => 'Invalid credentials'], 401);
    }

    public function logout()
    {
        session()->flush();
        return response()->json(['success' => true]);
    }
}
