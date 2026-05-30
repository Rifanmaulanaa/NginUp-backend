<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'owner') {
            return redirect('/host/account');
        }

        if ($user->role === 'admin') {
            return redirect('/admin/dashboard');
        }

        $ulasanCount = \App\Models\Review::where('id_user', $user->id_user)->count();
        $bookingCount = \App\Models\Pemesanan::where('id_user', $user->id_user)->count();

        return view('profile', compact('user', 'ulasanCount', 'bookingCount'));
    }

    public function rewards(): View
    {
        return view('rewards');
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'nama'  => 'required|string|max:200',
            'no_hp' => 'nullable|string|max:20',
        ]);

        $user->update($validated);

        return back()->with('success', 'Profil berhasil diperbarui');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'password_current' => 'required|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return back()->with('success', 'Password berhasil diperbarui');
    }
}
