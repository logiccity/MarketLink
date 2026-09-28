<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Farmer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    public function showLogin(Request $request)
    {
        $role = $request->query('role', 'customer');
        if (!in_array($role, ['customer', 'farmer', 'admin'], true)) {
            $role = 'customer';
        }

        return view('auth.login', compact('role'));
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['nullable', 'in:customer,farmer,admin'],
        ]);

        $remember = $request->boolean('remember');

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']], $remember)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();
        $user = Auth::user();

        if ($user->status !== 'active') {
            Auth::logout();
            return back()->withErrors([
                'email' => 'Your account is currently ' . $user->status . '. Please contact support.',
            ])->onlyInput('email');
        }

        return match ($user->role) {
            'admin' => redirect()->intended(route('admin.dashboard'))->with('success', 'Welcome back, Administrator ' . $user->name . '!'),
            'farmer' => redirect()->intended(route('farmer.dashboard'))->with('success', 'Welcome to your Farm Dashboard, ' . $user->name . '!'),
            default => redirect()->intended(route('customer.dashboard'))->with('success', 'Welcome back, ' . $user->name . '!'),
        };
    }

    public function showChooseRole()
    {
        return view('auth.choose-role');
    }

    public function showCustomerRegister()
    {
        return view('auth.register-customer');
    }

    public function registerCustomer(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Customer::create([
            'user_id' => $user->id,
            'preferences' => [],
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', 'Welcome to MarketLink! Your account has been created. Start exploring fresh local produce.');
    }

    public function showFarmerRegister()
    {
        return view('auth.register-farmer');
    }

    public function registerFarmer(Request $request)
    {
        $validated = $request->validate([
            'stall_name' => ['required', 'string', 'max:255'],
            'contact_person' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:25'],
            'address' => ['required', 'string', 'max:500'],
            'bio' => ['nullable', 'string', 'max:1000'],
            'operating_days' => ['nullable', 'array'],
            'pickup_windows' => ['nullable', 'string', 'max:255'],
            'password' => ['required', 'confirmed', Password::min(8)],
        ]);

        $user = User::create([
            'name' => $validated['contact_person'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'password' => Hash::make($validated['password']),
            'role' => 'farmer',
            'status' => 'active',
        ]);

        Farmer::create([
            'user_id' => $user->id,
            'stall_name' => $validated['stall_name'],
            'contact_person' => $validated['contact_person'],
            'phone' => $validated['phone'],
            'address' => $validated['address'],
            'bio' => $validated['bio'] ?? null,
            'pickup_windows' => $validated['pickup_windows'] ?? '9:00 AM - 1:00 PM',
            'operating_days' => $request->input('operating_days', ['Saturday', 'Sunday']),
            'order_cutoff_hours' => 12,
            'approval_status' => 'pending',
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('info', 'Welcome to MarketLink! Your farmer registration has been received and is pending administrator approval before public listings can be published.');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'You have been safely signed out.');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => ['required', 'email']]);
        $user = User::where('email', $request->email)->first();

        if ($user) {
            return back()->with('status', 'Password reset instructions have been sent to your email.');
        }

        return back()->withErrors(['email' => 'We could not find a user with that email address.']);
    }
}
