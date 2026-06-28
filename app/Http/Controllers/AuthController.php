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

    // Admin user editing
    public function editUser($userId)
    {
        $user = User::findOrFail($userId);
        $roles = Role::all();
        return view('admin.edit_user', compact('user', 'roles'));
    }

    public function updateUser(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $isSelf = ($user->user_id === Auth::user()->user_id);

        $rules = [
            'full_name' => 'required|string|max:120',
            'email' => 'required|email|unique:users,email,' . $user->user_id . ',user_id',
            'phone_number' => 'nullable|string|max:30',
            'role_id' => 'required|exists:roles,role_id',
            'account_status' => 'required|in:Active,Suspended,Disabled,Pending',
            'institution' => 'nullable|string|max:150',
            'specialisation' => 'nullable|string|max:100',
            'preferred_region' => 'nullable|string|max:100',
        ];

        // Conditional validations based on selected role
        if ($request->role_id == 2) { // Researcher
            $rules['institution'] = 'required|string|max:150';
        }

        $request->validate($rules);

        $user->full_name = $request->full_name;
        $user->email = $request->email;
        $user->phone_number = $request->phone_number;

        if (!$isSelf) {
            $user->role_id = $request->role_id;
            $user->account_status = $request->account_status;
        }

        $user->institution = $request->institution;
        $user->specialisation = $request->specialisation;
        $user->preferred_region = $request->preferred_region;

        $user->save();

        return redirect()->route('admin.dashboard')->with('success', "User account details for '{$user->full_name}' have been successfully updated.");
    }

    // Admin user deletion
    public function deleteUser($userId)
    {
        $user = User::findOrFail($userId);

        // Prevent admin self-deletion
        if ($user->user_id === Auth::user()->user_id) {
            return back()->withErrors(['error' => 'You cannot delete your own admin account.']);
        }

        $user->delete();

        return back()->with('success', "User '{$user->full_name}' has been permanently deleted.");
    }

    // Admin threshold updates
    public function updateThresholds(Request $request)
    {
        $request->validate([
            'low_min' => 'required|numeric|min:0|max:100',
            'low_max' => 'required|numeric|min:0|max:100|gte:low_min',
            'moderate_min' => 'required|numeric|min:0|max:100|gt:low_max',
            'moderate_max' => 'required|numeric|min:0|max:100|gte:moderate_min',
            'high_min' => 'required|numeric|min:0|max:100|gt:moderate_max',
            'high_max' => 'required|numeric|min:0|max:100|gte:high_min',
        ]);

        $threshold = \App\Models\VulnerabilityThreshold::first();

        if (!$threshold) {
            $threshold = new \App\Models\VulnerabilityThreshold();
            $threshold->threshold_name = 'Default Policy Set';
        }

        $threshold->low_min = $request->low_min;
        $threshold->low_max = $request->low_max;
        $threshold->moderate_min = $request->moderate_min;
        $threshold->moderate_max = $request->moderate_max;
        $threshold->high_min = $request->high_min;
        $threshold->high_max = $request->high_max;
        $threshold->created_by = Auth::user()->user_id;
        $threshold->save();

        return back()->with('success', 'Vulnerability evaluation threshold mappings updated successfully.');
    }
}