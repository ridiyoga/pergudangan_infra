<?php

namespace App\Http\Controllers;

use App\Models\HakAkses;
use App\Models\Karyawan;
use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    //
    public function index()
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('home', compact('parent'));
    }
}
