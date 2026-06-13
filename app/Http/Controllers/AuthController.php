<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Role;
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
            'account_status' => 'Active', // Public users auto-approved
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
            'phone_number' => 'required|string|max:30',
            'institution' => 'required|string|max:150',
            'password' => 'required|min:6|confirmed',
        ]);

        User::create([
            'role_id' => 2, // RESEARCHER
            'full_name' => $request->full_name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone_number' => $request->phone_number,
            'institution' => $request->institution,
            'account_status' => 'Pending', // Researchers must wait for admin approval
        ]);

        return redirect()->route('login')->with('success', 'Researcher registration submitted. Your account is pending review by the administrator.');
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
                
                $message = 'Your account status is currently ' . $user->account_status . '.';
                if ($user->account_status === 'Pending') {
                    $message = 'Your account is pending review.';
                } elseif ($user->account_status === 'Suspended') {
                    $message = 'Your account has been suspended.';
                } elseif ($user->account_status === 'Disabled') {
                    $message = 'Your account has been disabled.';
                }

                return back()->withErrors([
                    'email' => $message,
                ]);
            }

            // Redirect to appropriate dashboard based on role and record admin login
            if ($user->isAdmin()) {
                $user->last_login = now();
                $user->save();
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

    // My Account settings
    public function showAccount()
    {
        return view('auth.account');
    }

    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $rules = [
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone_number' => 'nullable|string|max:30',
            'password' => 'nullable|min:6|confirmed',
        ];

        if ($user->isPublic()) {
            $rules['preferred_region'] = 'nullable|string|max:100';
        } elseif ($user->isResearcher()) {
            $rules['specialisation'] = 'nullable|string|max:100';
        }

        $request->validate($rules);

        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        if ($user->isPublic()) {
            $user->preferred_region = $request->preferred_region;
        } elseif ($user->isResearcher()) {
            $user->specialisation = $request->specialisation;
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return back()->with('success', 'Account profile settings updated successfully.');
    }

    // Admin user status management
    public function updateUserStatus(Request $request, $userId)
    {
        $request->validate([
            'status' => 'required|in:Active,Suspended,Disabled,Pending',
        ]);

        $user = User::findOrFail($userId);
        
        // Prevent admins from locking themselves out of their dashboard status
        if ($user->user_id === Auth::user()->user_id) {
            return back()->withErrors(['error' => 'You cannot change your own admin account status.']);
        }

        $user->account_status = $request->status;
        $user->save();

        return back()->with('success', "User account for '{$user->full_name}' updated to status: {$request->status}.");
    }
}