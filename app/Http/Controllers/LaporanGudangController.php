<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\BarangMasuk;
use App\Models\Menu;
use App\Models\Jabatan;
use App\Models\HakAkses;
use App\Models\Karyawan;
use App\Models\Log;
use App\Models\Proyek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class LaporanGudangController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        $menu_id = $id;
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('gudang/barang_masuk/index', compact('parent', 'menu_id'));
    }
    public function laporan_gudang_dt(Request $request, $id)
    {
        // dd($request->all());

        $start = Carbon::parse($request->start_date)->startOfDay();
        $end = Carbon::parse($request->end_date)->endOfDay();

        $user = Auth::user();
        $data = BarangMasuk::where('status', 3)
            ->where('id_proyek', $user->id_proyek)
            ->whereBetween('created_at', array($start, $end))
            ->get();
        // dd($id);

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) use ($id) {
                $see = '<a href="' . url("/barang_masuk_detail/{$row->id}/{$id}") . '"
                            class="btn btn-info m-1"
                            data-id="{$row->id}">
                            <i class="bi bi-database-down"></i>
                        </a>';
                $update = $see . '<button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-no_bapb="' . $row->no_bapb . '">
                                <i class="bi bi-pencil"></i></button>';
                return $update;
            })
            ->addColumn('status', function ($row) use ($id) {
                if ($row->status == 3) {
                    return '<span class="badge bg-success p-2">DITANDATANGANI KEUANGAN</span>';
                } else {
                    return '<span class="badge bg-secondary p-2">BELUM DITANDATANGANI</span>';
                }
            })
            ->addColumn('ttd_karyawan', function ($row) {

                if (!$row->ttd_karyawan) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_karyawan));

                return '<img src="' . $signature . '"
                        style="
                            max-width: 120px;
                            max-height: 60px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            display: block;
                            margin: auto;
                        ">';
            })->addColumn('ttd_atasan2', function ($row) {

                if (!$row->ttd_atasan2) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_atasan2));

                return '<img src="' . $signature . '"
                        style="
                            max-width: 120px;
                            max-height: 60px;
                            width: auto;
                            height: auto;
                            object-fit: contain;
                            display: block;
                            margin: auto;
                        ">';
            })
            ->addColumn('created_at', function ($row) use ($id) {
                $created_at = Carbon::parse($row->created_at)->format('d-m-Y H:i:s');
                return $created_at;
            })

            ->rawColumns([
                'aksi',
                'status',
                'ttd_karyawan',
                'ttd_atasan2',
                'created_at',
            ])
            ->make(true);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'id_bapb_update' => 'required|exists:barang_masuk,id',
                'no_bapb' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'no_bapb.required' => 'No. BAPB wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $user = Auth::user();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            $menu = Menu::where('id', $request->menu_id)->first();
            $bapb_unik = BarangMasuk::where('no_bapb', $request->no_bapb)
                ->where('id_proyek', $user->id_proyek)
                ->where('id', '!=', $request->id_bapb_update)
                ->exists();
            // dd($bapb_unik);
            if ($bapb_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'UPDATE',
                    'deskripsi' => 'Tertolak karena tidak unik ' . $request->no_bapb,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'nama_tabel' => 'barang_bapb',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'No. BAPB sudah digunakan dalam proyek ini!'
                ], 422);
            }
            // dd($karyawan);
            $bapb = BarangMasuk::where('id', $request->id_bapb_update)->first();
            $bapb->update([
                'no_bapb' => $request->no_bapb,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Mengupdate BAPB ' . $request->no_bapb,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'nama_tabel' => 'barang_masuk',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'No. BAPB berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
