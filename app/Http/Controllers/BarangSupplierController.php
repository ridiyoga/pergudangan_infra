<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangSupplier;
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

class BarangSupplierController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('master/barang/barang_supplier', compact('parent'));
    }
    public function create(Request $request)
    {
        // dd($user);
        try {
            $rules = [
                'nama_supplier' => 'required',

            ];
            $messages = [
                'nama_supplier.required' => 'Supplier wajib diisi',
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
            $proyek = Proyek::where('id', $user->id_proyek)->first();
            $kode = Uuid::uuid4();
            $supplier_unik = BarangSupplier::where('nama_supplier', $request->nama_supplier)
                ->where('id_proyek', $user->id_proyek)
                ->exists();

            if ($supplier_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'CREATE',
                    'deskripsi' => 'Tertolak karena tidak unik ' . $request->nama_supplier,
                    'id_menu' => null,
                    'nama_menu' => 'Supplier',
                    'id_tabel_baru' => $kode,
                    'nama_tabel' => 'barang_supplier',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);
                return response()->json([
                    'status' => false,
                    'message' => 'Nama supplier sudah digunakan dalam proyek ini!'
                ], 422);
            }
            BarangSupplier::create([
                'id' => $kode,
                'nama_supplier' => $request->nama_supplier,
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
                'deskripsi' => 'Menambah supplier baru ' . $request->nama_supplier,
                'id_menu' => null,
                'nama_menu' => 'Supplier',
                'id_tabel_baru' => $kode,
                'nama_tabel' => 'barang_supplier',
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
    public function supplier_dt()
    {
        $user = Auth::user();
        $data = BarangSupplier::where('id_proyek', $user->id_proyek)->get();


        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_supplier="' . $row->nama_supplier . '">
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
    //             'id_supplier_delete' => 'required',

    //         ];
    //         $messages = [
    //             'id_supplier_delete.required' => 'Terjadi kesalahan',
    //         ];
    //         $validator = Validator::make($request->all(), $rules, $messages);
    //         if ($validator->fails()) {
    //             return response()->json([
    //                 'code' => 400,
    //                 'errors' => $validator->errors(),
    //             ]);
    //         }

    //         $menu = BarangSupplier::where('id', $request->id_supplier_delete)->first();

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
                'nama_supplier' => 'required',

            ];
            $messages = [
                'nama_supplier.required' => 'Supplier wajib diisi',
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
            $supplier_unik = BarangSupplier::where('nama_supplier', $request->nama_supplier)
                ->where('id_proyek', $user->id_proyek)
                ->where('id', '!=', $request->id_supplier_update)
                ->exists();

            if ($supplier_unik) {
                Log::create([
                    'id' => Uuid::uuid4(),
                    'id_karyawan' => $karyawan->id,
                    'nama_karyawan' => $karyawan->nama_karyawan,
                    'aksi' => 'UPDATE',
                    'deskripsi' => 'Tertolak karena tidak unik ' . $request->nama_supplier,
                    'id_menu' => null,
                    'nama_menu' => 'Supplier',
                    'id_tabel_baru' =>  $request->id_supplier_update,
                    'nama_tabel' => 'barang_supplier',
                    'id_tabel_lama' => null,
                    'keterangan' => 'failed',
                ]);

                return response()->json([
                    'status' => false,
                    'message' => 'Nama supplier sudah digunakan dalam proyek ini!'
                ], 422);
            }
            BarangSupplier::where('id', $request->id_supplier_update)->update([
                'nama_supplier' => $request->nama_supplier,
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'updated_at' => now(),
            ]);
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Update data supplier ' . $request->nama_supplier,
                'id_menu' => null,
                'nama_menu' => 'Supplier',
                'id_tabel_baru' =>  $request->id_supplier_update,
                'nama_tabel' => 'barang_supplier',
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
