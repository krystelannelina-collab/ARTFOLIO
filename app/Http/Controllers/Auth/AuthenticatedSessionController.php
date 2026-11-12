<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->role === 'admin') {
            return $this->redirectAdmin($request);
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /**
     * Send an admin to the admin area. A remembered client page (for example the
     * cart a guest tried to open before logging in) is ignored, so admins never
     * land on the storefront; a remembered admin page is still honoured.
     */
    private function redirectAdmin(Request $request): RedirectResponse
    {
        $intendedUrl = $request->session()->pull('url.intended');
        $adminAreaUrl = route('admin.dashboard');

        if (is_string($intendedUrl) && str_starts_with($intendedUrl, $adminAreaUrl)) {
            return redirect()->to($intendedUrl);
        }

        return redirect()->to($adminAreaUrl);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
