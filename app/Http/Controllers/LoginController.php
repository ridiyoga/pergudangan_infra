<?php

namespace App\Http\Controllers;

use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Ramsey\Uuid\Uuid;


class LoginController extends Controller
{
    public function index()
    {
        if (Auth::check()) {
            return redirect()->route('home');
        }

        return view('login');
    }

    public function proses_login(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required', 'string'],
            'password' => ['required', 'string'],
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = Auth::user();
            // dd($user);
            $request->session()->put([
                'user_id' => $user->getAuthIdentifier(),
                'username' => $user->username,
                'login_time' => now()->toDateTimeString(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => null,
                'nama_karyawan' => $request->username,
                'aksi' => 'auth',
                'deskripsi' => 'Login',
                'id_menu' => null,
                'nama_menu' => 'Login',
                'id_tabel_baru' => null,
                'nama_tabel' => 'master_user',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);

            return redirect()->intended(route('home'))->with('success', 'Selamat datang kembali!');
        }
        Log::create([
            'id' => Uuid::uuid4(),
            'id_karyawan' => null,
            'nama_karyawan' => $request->username,
            'aksi' => 'auth',
            'deskripsi' => 'Username atau password salah',
            'id_menu' => null,
            'nama_menu' => 'Login',
            'id_tabel_baru' => null,
            'nama_tabel' => 'master_user',
            'id_tabel_lama' => null,
            'keterangan' => 'failed',
        ]);
        return back()->withErrors([

            'username' => 'Username atau password yang Anda masukkan salah.',
        ])->onlyInput('username');
    }

    public function logout(Request $request)
    {
        Auth::logout();


        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $session_id = session()->getId();
        dd($session_id);

        Log::create([
            'id' => Uuid::uuid4(),
            'id_karyawan' => null,
            'nama_karyawan' => $request->username,
            'aksi' => 'auth',
            'deskripsi' => 'Logout',
            'id_menu' => null,
            'nama_menu' => 'Logout',
            'id_tabel_baru' => null,
            'nama_tabel' => 'master_user',
            'id_tabel_lama' => null,
            'keterangan' => 'success',
        ]);

        return redirect()->route('login')->with('success', 'Anda telah berhasil keluar.');
    }
}
