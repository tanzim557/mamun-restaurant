<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $login    = trim($request->input('email', '') ?: $request->input('username', ''));
        $password = trim($request->input('password', ''));

        // Find by email OR by phone (username) — only ADMIN role
        $user = User::where('role', 'ADMIN')
            ->where(function ($q) use ($login) {
                $q->where('email', $login)->orWhere('phone', $login);
            })
            ->first();

        if ($user && Hash::check($password, $user->password)) {
            session(['admin_logged_in' => true, 'admin_user' => $user->toArray()]);
            return response()->json(['success' => true, 'user' => $user]);
        }

        return response()->json(['success' => false, 'error' => 'ভুল Username বা Password. আবার চেষ্টা করুন।'], 401);
    }

    public function logout()
    {
        session()->flush();
        return response()->json(['success' => true]);
    }

    // ── Customer Authentication ──
    public function customerRegister(Request $request)
    {
        $name = trim($request->input('name', ''));
        $phone = trim($request->input('phone', ''));
        $email = trim($request->input('email', ''));
        $password = trim($request->input('password', ''));
        $address = trim($request->input('address', ''));

        if (empty($name) || empty($phone) || empty($password)) {
            return response()->json([
                'success' => false,
                'error' => 'নাম, মোবাইল নম্বর ও পাসওয়ার্ড আবশ্যক।'
            ], 400);
        }

        // Check if phone or email already registered
        $existing = User::where('phone', $phone)
            ->orWhere(function ($query) use ($phone) {
                $query->where('email', $phone);
            })
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'error' => 'এই মোবাইল নম্বরে ইতোমধ্যে অ্যাকাউন্ট আছে। লগইন করুন।'
            ], 409);
        }

        // Use email if given, or generate unique customer identifier
        if (empty($email)) {
            $email = $phone . '@customer.nazrulhotel.com';
        }

        $user = User::create([
            'name' => $name,
            'phone' => $phone,
            'email' => $email,
            'address' => $address,
            'password' => Hash::make($password),
            'role' => 'CUSTOMER'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'অ্যাকাউন্ট সফলভাবে তৈরি হয়েছে!',
            'customer' => $user->toArray()
        ], 201);
    }

    public function customerLogin(Request $request)
    {
        $login = trim($request->input('login', ''));
        $password = trim($request->input('password', ''));

        if (empty($login) || empty($password)) {
            return response()->json([
                'success' => false,
                'error' => 'মোবাইল নম্বর ও পাসওয়ার্ড প্রদান করুন।'
            ], 400);
        }

        $user = User::where('phone', $login)
            ->orWhere('email', $login)
            ->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'error' => 'অ্যাকাউন্ট পাওয়া যায়নি। অনুগ্রহ করে সাইনআপ করুন।'
            ], 404);
        }

        if (!Hash::check($password, $user->password) && $user->password !== $password) {
            return response()->json([
                'success' => false,
                'error' => 'ভুল পাসওয়ার্ড। আবার চেষ্টা করুন।'
            ], 401);
        }

        return response()->json([
            'success' => true,
            'message' => 'স্বাগতম, ' . $user->name . '!',
            'customer' => $user->toArray()
        ], 200);
    }

    public function customerUpdateProfile(Request $request)
    {
        $id = $request->input('id');
        $user = User::find($id);
        if (!$user) {
            return response()->json(['success' => false, 'error' => 'Customer not found'], 404);
        }

        if ($request->filled('name')) $user->name = $request->input('name');
        if ($request->filled('address')) $user->address = $request->input('address');
        if ($request->filled('phone')) $user->phone = $request->input('phone');
        if ($request->filled('password')) $user->password = Hash::make($request->input('password'));
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'প্রোফাইল আপডেট সফল হয়েছে!',
            'customer' => $user->toArray()
        ]);
    }
}
