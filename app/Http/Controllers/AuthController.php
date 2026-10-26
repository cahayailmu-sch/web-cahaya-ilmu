<?php

namespace App\Http\Controllers;

use App\Support\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class AuthController extends Controller
{
    /**
     * Show the admin login form.
     */
    public function showLogin(): View|RedirectResponse
    {
        if (Auth::check()) {
            return redirect()->route('admin.dashboard');
        }

        return view('admin.auth.login');
    }

    /**
     * Authenticate the admin user.
     */
    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'string', 'email'],
            'password' => ['required', 'string'],
        ]);

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Email atau password yang Anda masukkan salah.',
            ]);
        }

        $request->session()->regenerate();

        $user = Auth::user();
        if ($user) {
            ActivityLogger::log('login', 'Autentikasi', "Login ke panel admin sebagai {$user->name}.", null, $user);
        }

        return redirect()->intended(route('admin.dashboard'));
    }

    /**
     * Log the admin user out.
     */
    public function logout(Request $request): RedirectResponse
    {
        $user = Auth::user();
        if ($user) {
            ActivityLogger::log('logout', 'Autentikasi', "Logout dari panel admin ({$user->name}).", null, $user);
        }

        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login')->with('status', 'Anda telah berhasil keluar.');
    }
}
