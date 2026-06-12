<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // General Public Registration
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6|confirmed',
            'phone_number' => 'nullable|string|max:30',
        ]);

        User::create([
            'role_id' => 1, // GENERAL_PUBLIC
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'account_status' => 'Active',
        ]);

        return redirect()->route('login')->with('success', 'Observer account created successfully. Please log in.');
    }

    // Researcher Registration
    public function showRegisterResearcher()
    {
        return view('auth.register_researcher');
    }

    public function registerResearcher(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email',
            'phone_number' => 'required|string|max:30', // Required for researchers
            'institution' => 'required|string|max:150', // Required for researchers
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'institution' => $request->institution,
            'account_status' => 'Active',
        ]);

        return redirect()->route('login')->with('success', 'Researcher account created successfully. Please log in.');
    }

    // Login & Logout
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->account_status !== 'Active') {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Your account is currently ' . $user->account_status . '.',
                ]);
            }

            // Redirect to appropriate dashboard based on role
            if ($user->isAdmin()) {
                return redirect()->route('admin.dashboard')->with('success', 'Logged in as Administrator.');
            } elseif ($user->isResearcher()) {
                return redirect()->route('researcher.dashboard')->with('success', 'Logged in as Researcher.');
            } else {
                return redirect()->route('public.dashboard')->with('success', 'Logged in successfully.');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Logged out successfully.');
    }
}