<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class AuthController extends Controller
{
    public const DEFAULT_ADMIN_NAME = 'ARTFOLIO Admin';

    public const DEFAULT_ADMIN_EMAIL = 'admin@artfolio.com';

    public const DEFAULT_ADMIN_PASSWORD = 'admin123';

    public function showLogin(): View
    {
        return view('client-login');
    }

    public function showRegister(): View
    {
        return view('client-signup');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users', 'email')],
            'password' => ['required', 'string', 'min:8'],
        ], [
            'email.unique' => 'An account with this email address already exists.',
            'password.min' => 'Password must be at least 8 characters.',
        ]);

        User::query()->create([
            'name' => trim($validated['name']),
            'email' => trim($validated['email']),
            'password' => Hash::make($validated['password']),
            'role' => 'client',
        ]);

        return redirect()->route('login')
            ->with('success', 'Account created successfully. Please log in.');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $normalizedEmail = mb_strtolower(trim($credentials['email']));

        if ($normalizedEmail === self::DEFAULT_ADMIN_EMAIL) {
            User::query()->firstOrCreate(
                ['email' => self::DEFAULT_ADMIN_EMAIL],
                [
                    'name' => self::DEFAULT_ADMIN_NAME,
                    'password' => Hash::make(self::DEFAULT_ADMIN_PASSWORD),
                    'role' => 'admin',
                ],
            );
            $credentials['email'] = self::DEFAULT_ADMIN_EMAIL;
        }

        $user = User::query()->where('email', $credentials['email'])->first();

        if (! $user) {
            return back()->withErrors([
                'email' => 'No account found. Please create an account first.',
            ])->onlyInput('email');
        }

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            return back()->withErrors([
                'password' => 'Incorrect password. Please try again.',
            ])->onlyInput('email');
        }

        $request->session()->regenerate();

        if (Auth::user()?->role === 'admin') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(route('home'));
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}
