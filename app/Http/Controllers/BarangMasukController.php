<?php

namespace App\Http\Controllers;

use App\Models\BarangMasuk;
use App\Models\Menu;
use App\Models\Jabatan;
use App\Models\HakAkses;
use App\Models\Karyawan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class BarangMasukController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        return view('master/barang/barang_masuk', compact('parent'));
    }
    public function create(Request $request)
    {
        try {
            $rules = [
                'no_bapb' => 'required',

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

            BarangMasuk::create([
                'id' => Uuid::uuid4(),
                'no_bapb' => $request->no_bapb,
                'created_at' => now(),
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
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function delete(Request $request)
    {
        try {
            $rules = [
                'id_barang_masuk_delete' => 'required',

            ];
            $messages = [
                'id_barang_masuk_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $menu = BarangMasuk::where('id', $request->id_barang_masuk_delete)->first();

            $menu->delete();

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

            BarangMasuk::where('id', $request->id_barang_masuk_update)->update([
                'no_bapb' => $request->no_bapb,
                'updated_at' => now(),
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
