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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class BarangMasukController extends Controller
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
    public function create(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
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
            $kode = Uuid::uuid4();


            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            $area = Area::where('id', $karyawan->id_area)->first();
            $proyek = Proyek::where('id', $user->id_proyek)->first();
            $kode = Uuid::uuid4();
            $bapb_unik = BarangMasuk::where('no_bapb', $request->no_bapb)
                ->where('id_proyek', $user->id_proyek)
                ->exists();
            if ($bapb_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'CREATE',
                    'deskripsi' => 'Tertolak karena NO. BAPB tidak unik ' . $request->no_bapb,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'id_tabel_baru' => $kode,
                    'nama_tabel' => 'barang_masuk',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'No. BAPB sudah digunakan dalam proyek ini!'
                ], 422);
            }
            // dd($area);
            $image = $request->signature;
            $image = str_replace('data:image/png;base64,', '', $image);
            $image = str_replace(' ', '+', $image);

            $fileName = 'signature_' . time() . '.png';
            // dd($fileName);
            // simpan ke storage
            Storage::disk('public')->put(
                'signatures/' . $fileName,
                base64_decode($image)
            );
            BarangMasuk::create([
                'id' => $kode,
                'no_bapb' => $request->no_bapb,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'ttd_karyawan' => $fileName,

                'id_area' => $area->id,
                'nama_area' => $area->nama_area,

                'id_proyek' => $proyek->id,
                'nama_proyek' => $proyek->nama_proyek,
                'created_at' => now(),
            ]);

            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'CREATE',
                'deskripsi' => 'Menambah BAPB baru ' . $request->no_bapb,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' => $kode,
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
    public function barang_masuk_dt()
    {
        $data = BarangMasuk::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-no_bapb="' . $row->no_bapb . '">
                                <i class="bi bi-pencil"></i></button>';
                $btn = $action . '<button id="btn_delete_datatable" class="btn btn-danger m-1" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>';
                return $btn;
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
            })
            ->rawColumns([
                'aksi',
                'ttd_karyawan'
            ])
            ->make(true);
    }
    public function delete(Request $request)
    {
        try {
            $rules = [
                'id_bapb_delete' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'id_bapb_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $bapb = BarangMasuk::where('id', $request->id_bapb_delete)->first();
            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'DELETE',
                'deskripsi' => 'Menghapus BAPB ' . $bapb->no_bapb,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'nama_tabel' => 'barang_masuk',
                'id_tabel_lama' => $bapb->id,
                'keterangan' => 'success',
            ]);

            $bapb->delete();

            return response()->json([
                'success' => true,
                'message' => 'No. BAPB berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'no_bapb' => 'required',
                'ttd_karyawan' => 'nullable|string',
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
}
