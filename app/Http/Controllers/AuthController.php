<?php
// app/Http/Controllers/AuthController.php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AuthController extends Controller
{
    // Show register form (GET)
    public function showRegister()
    {
        return view('auth.register');
    }

    // Handle register form submission (POST)
    public function register(Request $request)
    {
        // Validate input
        $request->validate([
            'name'     => 'required|min:2',
            'email'    => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed', // confirmed = needs password_confirmation field
        ]);

        // Create user using OOP
        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password), // hash the password
            'role'     => 'user', // default role
        ]);

        return redirect()->route('login')->with('success', 'Account created! Please login.');
    }

    // Show login form (GET)
    public function showLogin()
    {
        return view('auth.login');
    }

    // Handle login form submission (POST)
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        // Find user by email
        $user = User::where('email', $request->email)->first();

        // Check if user exists and password matches
        if ($user && Hash::check($request->password, $user->password)) {
            // Store user in session
            Session::put('user_id', $user->id);
            Session::put('user_name', $user->name);
            Session::put('user_role', $user->role);

            // Redirect based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard');
            }
            return redirect()->route('events.index');
        }

        // Login failed
        return back()->withErrors(['email' => 'Invalid email or password.']);
    }

    // Logout
    public function logout()
    {
        Session::flush(); // clear all session data
        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}