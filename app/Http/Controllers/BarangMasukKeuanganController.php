<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\BarangMasuk;
use App\Models\BarangMasukDetail;
use App\Models\Menu;
use App\Models\Jabatan;
use App\Models\HakAkses;
use App\Models\Karyawan;
use App\Models\Log;
use App\Models\Proyek;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;
use Barryvdh\DomPDF\Facade\Pdf;

class BarangMasukKeuanganController extends Controller
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
        return view('keuangan/barang_masuk_keuangan/index', compact('parent', 'menu_id'));
    }
    public function barang_masuk_keuangan_dt($id)
    {
        $user = Auth::user();
        $data = BarangMasuk::where('status', 2)->where('id_proyek', $user->id_proyek)->get();
        // dd($id);

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) use ($id) {
                $see = '<a href="' . url("/barang_masuk_keuangan_detail/{$row->id}/{$id}") . '"
                            class="btn btn-info m-1"
                            data-id="{$row->id}">
                            <i class="bi bi-database-down"></i>
                        </a>';
                return $see;
            })
            ->addColumn('status', function ($row) use ($id) {

                if ($row->status == 3) {
                    return '<span class="badge bg-success p-2">DITANDATANGANI KEUANGAN</span>';
                } else {
                    return '<span class="badge bg-secondary p-2">BELUM DITANDATANGANI</span>';
                }
            })

            ->rawColumns([
                'aksi',
                'status',
                'harga',
            ])
            ->make(true);
    }
    public function barang_masuk_keuangan_dt2(Request $request, $id)
    {
        // dd($request->all());
        $start = Carbon::parse($request->start_date)->format('Y-m-d');
        $end = Carbon::parse($request->end_date)->format('Y-m-d');


        $user = Auth::user();
        $data = BarangMasuk::where('status', 3)
            ->where('id_proyek', $user->id_proyek)
            ->whereBetween('tanggal_masuk', array($start, $end))
            ->get();
        // dd($id);

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) use ($id) {
                $see = '<a href="' . url("/barang_masuk_keuangan_detail/{$row->id}/{$id}") . '"
                            class="btn btn-info m-1"
                            data-id="{$row->id}">
                            <i class="bi bi-database-down"></i>
                        </a>';
                $action = $see . '<a href="' . url("/keuangan_cetak_pdf/{$row->id}/") . '"
                            class="btn btn-danger m-1"
                            data-id="{$row->id}">
                           PDF
                        </a>';
                return $action;
            })
            ->addColumn('status', function ($row) use ($id) {
                if ($row->status == 3) {
                    return '<span class="badge bg-success p-2">DITANDATANGANI KEUANGAN</span>';
                } else {
                    return '<span class="badge bg-secondary p-2">BELUM DITANDATANGANI</span>';
                }
            })->addColumn('ttd_atasan3', function ($row) {

                if (!$row->ttd_atasan3) {
                    return '-';
                }

                $signature = asset('storage/signatures/' . basename($row->ttd_atasan3));

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
            ->addColumn('waktu_atasan3', function ($row) use ($id) {
                $waktu_atasan3 = Carbon::parse($row->waktu_atasan3)->format('d-m-Y H:i:s');
                return $waktu_atasan3;
            })

            ->rawColumns([
                'aksi',
                'status',
                'ttd_karyawan',
                'ttd_atasan3',
                'waktu_atasan3',
            ])
            ->make(true);
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'no_bapb' => 'required',
                'signature' => [
                    'required',
                    'string',
                    'regex:/\Adata:image\/png;base64,[A-Za-z0-9+\/]+={0,2}\z/',
                ],
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'no_bapb.required' => 'No. BAPB wajib diisi',
                'signature.required' => 'Tanda tangan wajib diisi',
                'signature.string' => 'Format tanda tangan tidak valid',
                'signature.regex' => 'Format tanda tangan tidak valid',
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
            $area = Area::where('id', $karyawan->id_area)->first();
            $proyek = Proyek::where('id', $user->id_proyek)->first();
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
            if ($bapb->ttd_karyawan) {
                $oldSignaturePath = 'signatures/' . basename($bapb->ttd_karyawan);
                if (Storage::disk('public')->exists($oldSignaturePath)) {
                    Storage::disk('public')->delete($oldSignaturePath);
                }
            }
            $image = $request->signature;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            // nama file
            $fileName = 'signature_' . time() . '.png';
            // dd($fileName);
            // simpan ke storage
            Storage::disk('public')->put(
                'signatures/' . $fileName,
                base64_decode($image)
            );

            $bapb->update([
                'no_bapb' => $request->no_bapb,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'ttd_karyawan' => $fileName,

                'id_atasan1' => $karyawan->id,
                'nama_atasan1' => $karyawan->nama_karyawan,
                'ttd_atasan1' => $fileName,

                'id_area' => $area->id,
                'nama_area' => $area->nama_area,

                'id_proyek' => $proyek->id,
                'nama_proyek' => $proyek->nama_proyek,
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
    public function keuangan_cetak_pdf($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $bapb = BarangMasuk::where('id', $id)->first();
        $proyek = Proyek::where('id', $user->id_proyek)->first();
        $pdf = Pdf::loadView('keuangan.barang_masuk_keuangan.cetak_pdf', [
            'user' => $user,
            'karyawan' => $karyawan,
            'bapb' => $bapb,
            'proyek' => $proyek,
        ]);

        $pdf->setPaper('A4', 'landscape');

        return $pdf->stream('laporan_barang_masuk.pdf');
    }
}
