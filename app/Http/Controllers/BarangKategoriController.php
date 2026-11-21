<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangKategori;
use App\Models\Menu;
use App\Models\HakAkses;
use App\Models\Karyawan;
use App\Models\Log;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class BarangKategoriController extends Controller
{
    public function index($id)
    {
        $menu_id = $id;
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('master/barang/barang_kategori', compact('parent', 'menu_id'));
    }
    public function create(Request $request)
    {
        // dd($user);
        try {
            $rules = [
                'nama_kategori' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'nama_kategori.required' => 'Kategori wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            $proyek = Proyek::where('id', $user->id_proyek)->first();
            $kode = Uuid::uuid4();
            $barang_unik = BarangKategori::where('nama_kategori', $request->nama_kategori)
                ->where('id_proyek', $user->id_proyek)
                ->exists();

            if ($barang_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'CREATE',
                    'deskripsi' => 'Tertolak karena tidak unik ' . $request->nama_kategori,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'id_tabel_baru' => $kode,
                    'nama_tabel' => 'barang_kategori',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Nama kategori sudah digunakan dalam proyek ini!'
                ], 422);
            }
            BarangKategori::create([
                'id' => $kode,
                'nama_kategori' => $request->nama_kategori,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'id_proyek' => $proyek->id,
                'nama_proyek' => $proyek->nama_proyek,
                'created_at' => now(),
            ]);

            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'CREATE',
                'deskripsi' => 'Menambah barang baru ' . $request->nama_kategori,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' => $kode,
                'nama_tabel' => 'barang_kategori',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function kategori_dt()
    {
        $user = Auth::user();
        $data = BarangKategori::where('id_proyek', $user->id_proyek)->get();


        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_kategori="' . $row->nama_kategori . '">
                                 <i class="bi bi-pencil"></i></button>';
                // $btn = $action . '<button id="btn_delete_datatable" class="btn btn-danger m-1" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>';
                return $action;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    // public function delete(Request $request)
    // {
    //     try {
    //         $rules = [
    //             'id_kategori_delete' => 'required',

    //         ];
    //         $messages = [
    //             'id_kategori_delete.required' => 'Terjadi kesalahan',
    //         ];
    //         $validator = Validator::make($request->all(), $rules, $messages);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'code' => 400,
    //                 'errors' => $validator->errors(),
    //             ]);
    //         }

    //         $menu = BarangKategori::where('id', $request->id_kategori_delete)->first();

    //         $menu->delete();

    //         return response()->json([
    //             'success' => true,
    //             'message' => 'Barang berhasil disimpan',
    //         ], 201);
    //     } catch (\Exception $e) {

    //         return $e->getMessage();
    //     }
    // }
    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'nama_kategori' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'nama_kategori.required' => 'Kategori wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $user = Auth::user();
            $menu = Menu::where('id', $request->menu_id)->first();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            $kategori_unik = BarangKategori::where('nama_kategori', $request->nama_kategori)
                ->where('id_proyek', $user->id_proyek)
                ->where('id', '!=', $request->id_kategori_update)
                ->exists();

            if ($kategori_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'UPDATE',
                    'deskripsi' => 'Tertolak karena tidak unik ' . $request->nama_kategori,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'id_tabel_baru' =>  $request->id_kategori_update,
                    'nama_tabel' => 'barang_kategori',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Nama kategori sudah digunakan dalam proyek ini!'
                ], 422);
            }
            BarangKategori::where('id', $request->id_kategori_update)->update([
                'nama_kategori' => $request->nama_kategori,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Update data barang ' . $request->nama_kategori,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' =>  $request->id_kategori_update,
                'nama_tabel' => 'barang_kategori',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);


            return response()->json([
                'success' => true,
                'message' => 'Barang berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
