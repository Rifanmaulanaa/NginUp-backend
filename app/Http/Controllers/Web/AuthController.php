<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class AuthController extends Controller
{
    public function showLogin(): View
    {
        return view('auth.login');
    }

    public function login(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email'    => 'required|string',
            'password' => 'required|string',
        ]);

        if (Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])
            || Auth::attempt(['username' => $credentials['email'], 'password' => $credentials['password']])) {
            $request->session()->regenerate();

            $user = Auth::user();

            return match ($user->role) {
                'admin'    => redirect()->intended('/admin/dashboard'),
                'owner'    => redirect()->intended('/host/dashboard'),
                default    => redirect()->intended('/home'),
            };
        }

        return back()->withErrors(['email' => 'Email atau password salah']);
    }

    public function showRegister(): View
    {
        return view('auth.register');
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'nama'     => 'required|string|max:200',
            'username' => 'required|string|max:200|unique:users,username',
            'email'    => 'required|email|max:200|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'no_hp'    => 'nullable|string|max:20',
            'role'     => 'nullable|in:traveler,owner',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        $validated['status'] = 'active';

        User::create($validated);

        return redirect('/login')->with('success', 'Pendaftaran berhasil! Silakan masuk menggunakan akun Anda.');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
