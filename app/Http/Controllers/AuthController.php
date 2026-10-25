<?php

namespace App\Http\Controllers;

use App\Mail\WelcomeCustomerMail;
use App\Models\ActivityLog;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $remember = $request->boolean('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if ($user->status === 'suspended') {
                Auth::logout();

                return back()->withErrors(['email' => 'Your account has been suspended. Please contact customer support.']);
            }

            ActivityLog::log('USER_LOGIN', "User {$user->email} logged in.", null, $user);

            return $this->redirectBasedOnRole($user)->with('success', "Welcome back, {$user->name}!");
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    public function showRegister(): View|RedirectResponse
    {
        if (Auth::check()) {
            return $this->redirectBasedOnRole(Auth::user());
        }

        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:30'],
            'driver_license_number' => ['nullable', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'driver_license_number' => $validated['driver_license_number'] ?? null,
            'password' => Hash::make($validated['password']),
            'role' => 'customer',
            'status' => 'active',
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        ActivityLog::log('USER_REGISTER', "Customer account created for {$user->email}.", null, $user);

        event(new Registered($user));

        try {
            Mail::to($user->email)->send(new WelcomeCustomerMail($user));
        } catch (\Throwable $e) {
            Log::error('Failed to send welcome email to customer: '.$e->getMessage(), [
                'user_id' => $user->id,
                'email' => $user->email,
            ]);
        }

        return redirect()->route('customer.dashboard')->with('success', 'Your account has been created successfully!');
    }

    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLog::log('USER_LOGOUT', "User {$user->email} logged out.", null, $user);
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')->with('info', 'You have been logged out.');
    }

    /**
     * Demo role switcher for testing and evaluators.
     */
    public function demoLogin(string $role): RedirectResponse
    {
        $roleMap = [
            'admin' => 'admin@autodrive.com',
            'rental' => 'rental@autodrive.com',
            'sales' => 'sales@autodrive.com',
            'support' => 'support@autodrive.com',
            'customer' => 'customer@autodrive.com',
        ];

        if (! isset($roleMap[$role])) {
            return redirect()->route('login')->withErrors(['error' => 'Invalid demo role selected.']);
        }

        $user = User::where('email', $roleMap[$role])->first();

        if ($user) {
            Auth::login($user);
            request()->session()->regenerate();
            ActivityLog::log('DEMO_LOGIN', "Switched into demo persona: {$user->role} ({$user->name}).", null, $user);

            return $this->redirectBasedOnRole($user)->with('success', "Logged in as {$user->name} ({$user->role}) via Demo Switcher.");
        }

        return redirect()->route('login')->withErrors(['error' => 'Demo account could not be found.']);
    }

    private function redirectBasedOnRole(User $user): RedirectResponse
    {
        return match ($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'rental_manager' => redirect()->route('rental.dashboard'),
            'sales_manager' => redirect()->route('sales.dashboard'),
            'customer_support' => redirect()->route('support.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    }
}
