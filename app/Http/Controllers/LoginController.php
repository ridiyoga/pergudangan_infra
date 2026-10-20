<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function index()
    {
        // dd($proyek);
        return view('login');
    }
    public function proses_login(Request $request)
    {
        dd($request->all());
        // Validasi input
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        // Cek autentikasi (menggunakan Auth bawaan Laravel)
        if (Auth::attempt($credentials, $request->has('remember'))) {
            // Mencegah Session Fixation Attack
            $request->session()->regenerate();

            // Mengambil data user yang berhasil login
            $user = Auth::user();

            // Menyimpan data custom ke Session (Username, Email, Role, dll)
            session([
                'user_id'   => $user->id,
                'username'  => $user->name,
                'email'     => $user->email,
                'role'      => $user->role ?? 'user', // Opsional jika ada kolom role
                'login_time' => now()->format('Y-m-d H:i:s')
            ]);

            // Redirect ke halaman dashboard
            return redirect()->intended('/dashboard')->with('success', 'Selamat datang kembali!');
        }

        // Jika login gagal
        return back()->withErrors([
            'email' => 'Email atau password yang Anda masukkan salah.',
        ])->onlyInput('email');
    }

    // 3. Memproses Logout & Menghapus Session
    public function logout(Request $request)
    {
        Auth::logout();

        // Hapus semua session & buat token CSRF baru
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login')->with('success', 'Anda telah berhasil keluar.');
    }
}
