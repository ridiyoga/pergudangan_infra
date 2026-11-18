<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Log;
use App\Models\HakAkses;
use App\Models\Karyawan;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class MasterLogController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('log', compact('parent'));
    }
    public function log_dt()
    {
        $data = Log::query();

        return DataTables::of($data)
            ->addColumn('created_at', function ($row) {
                return $row->created_at->translatedFormat('d-m-y, H:i:s');
            })

            ->make(true);
    }
}
