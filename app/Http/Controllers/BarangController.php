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

class BarangController extends Controller
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
        $kategori = BarangKategori::where('id_proyek', $user->id_proyek)->get();
        return view('master/barang/index', compact('parent', 'kategori', 'menu_id'));
    }
    public function create(Request $request)
    {
        // dd($user);
        try {
            $rules = [
                'nama_barang' => 'required',
                'kode_barang' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'nama_barang.required' => 'Nama Barang wajib diisi',
                'kode_barang.required' => 'Kode Barang wajib diisi',
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
            $kategori = BarangKategori::where('id', $request->id_kategori)->first();
            $kode = Uuid::uuid4();
            $barang_unik = Barang::where('nama_barang', $request->nama_barang)
                ->where('id_proyek', $user->id_proyek)
                ->exists();
            $kode_unik = Barang::where('kode_barang', $request->kode_barang)
                ->where('id_proyek', $user->id_proyek)
                ->exists();
            if ($kode_unik) {
                if ($barang_unik) {
                    Log::create([
                        'id' => Uuid::uuid4(),
                        'id_karyawan' => $karyawan->id,
                        'nama_karyawan' => $karyawan->nama_karyawan,
                        'aksi' => 'CREATE',
                        'deskripsi' => 'Tertolak karena nama tidak unik ' . $request->nama_barang,
                        'id_menu' => $menu->id,
                        'nama_menu' => $menu->nama_menu,
                        'id_tabel_baru' => $kode,
                        'nama_tabel' => 'barang',
                        'id_tabel_lama' => null,
                        'keterangan' => 'failed',
                    ]);
                    return response()->json([
                        'status' => false,
                        'message' => 'Nama barang sudah digunakan dalam proyek ini!'
                    ], 422);
                }
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'CREATE',
                    'deskripsi' => 'Tertolak karena kode tidak unik ' . $request->kode_barang,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'id_tabel_baru' => $kode,
                    'nama_tabel' => 'barang',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Kode barang sudah digunakan dalam proyek ini!'
                ], 422);
            }
            Barang::create([
                'id' => $kode,
                'nama_barang' => $request->nama_barang,
                'kode_barang' => $request->kode_barang,
                'satuan' => $request->satuan,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'id_kategori' => $kategori->id,
                'nama_kategori' => $kategori->nama_kategori,
                'id_proyek' => $proyek->id,
                'nama_proyek' => $proyek->nama_proyek,
                'created_at' => now(),
            ]);

            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'CREATE',
                'deskripsi' => 'Menambah barang baru ' . $request->nama_barang,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' => $kode,
                'nama_tabel' => 'barang',
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
    public function barang_dt()
    {
        $user = Auth::user();
        $data = Barang::where('id_proyek', $user->id_proyek)->get();


        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-satuan="' . $row->satuan . '"
                                data-kode_barang="' . $row->kode_barang . '"
                                data-nama_barang="' . $row->nama_barang . '"
                                data-id_kategori="' . $row->id_kategori . '">
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
    //             'id_barang_delete' => 'required',

    //         ];
    //         $messages = [
    //             'id_barang_delete.required' => 'Terjadi kesalahan',
    //         ];
    //         $validator = Validator::make($request->all(), $rules, $messages);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'code' => 400,
    //                 'errors' => $validator->errors(),
    //             ]);
    //         }

    //         $menu = Barang::where('id', $request->id_barang_delete)->first();

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
                'nama_barang' => 'required',
                'kode_barang' => 'required',
                'menu_id' => 'required|uuid|exists:master_menu,id',

            ];
            $messages = [
                'nama_barang.required' => 'Nama Barang wajib diisi',
                'kode_barang.required' => 'Kode Barang wajib diisi',
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
            $barang_unik = Barang::where('nama_barang', $request->nama_barang)
                ->where('id_proyek', $user->id_proyek)
                ->where('id', '!=', $request->id_barang_update)
                ->exists();
            $kode_unik = Barang::where('kode_barang', $request->kode_barang)
                ->where('id_proyek', $user->id_proyek)
                ->where('id', '!=', $request->id_barang_update)
                ->exists();
            $kategori = BarangKategori::where('id', $request->id_kategori)->first();

            if ($kode_unik) {
                if ($barang_unik) {
                    Log::create([
                        'id' => Uuid::uuid4(),
                        'id_karyawan' => $karyawan->id,
                        'nama_karyawan' => $karyawan->nama_karyawan,
                        'aksi' => 'UPDATE',
                        'deskripsi' => 'Tertolak karena nama tidak unik ' . $request->nama_barang,
                        'id_menu' => $menu->id,
                        'nama_menu' => $menu->nama_menu,
                        'id_tabel_baru' =>  $request->id_barang_update,
                        'nama_tabel' => 'barang',
                        'id_tabel_lama' => null,
                        'keterangan' => 'failed',
                    ]);

                    return response()->json([
                        'status' => false,
                        'message' => 'Nama barang sudah digunakan dalam proyek ini!'
                    ], 422);
                }
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'UPDATE',
                    'deskripsi' => 'Tertolak karena kode tidak unik ' . $request->kode_barang,
                    'id_menu' => $menu->id,
                    'nama_menu' => $menu->nama_menu,
                    'id_tabel_baru' =>  $request->id_barang_update,
                    'nama_tabel' => 'barang',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Kode barang sudah digunakan dalam proyek ini!'
                ], 422);
            }
            Barang::where('id', $request->id_barang_update)->update([
                'nama_barang' => $request->nama_barang,
                'kode_barang' => $request->kode_barang,
                'satuan' => $request->satuan,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'id_kategori' => $kategori->id,
                'nama_kategori' => $kategori->nama_kategori,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Update data barang ' . $request->nama_barang,
                'id_menu' => $menu->id,
                'nama_menu' => $menu->nama_menu,
                'id_tabel_baru' =>  $request->id_barang_update,
                'nama_tabel' => 'barang',
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
