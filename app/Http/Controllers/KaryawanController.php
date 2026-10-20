<?php

namespace App\Http\Controllers;

use App\Models\Area;
use App\Models\Jabatan;
use App\Models\Menu;
use App\Models\karyawan;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class KaryawanController extends Controller
{
    public function index()
    {
        $menu = Menu::orderBy('nama_menu')->get();
        $jabatan = Jabatan::orderBy('nama_jabatan')->get();
        $area = Area::orderBy('nama_area')->get();
        return view('master/karyawan/index', compact(
            'menu',
            'jabatan',
            'area'
        ));
    }
    public function create(Request $request)
    {
        // dd($request->all());
        try {
            $rules = [
                'nama_karyawan' => 'required',
                'email' => 'required',
                'no_wa' => 'required',
                'id_jabatan' => 'required',
                'is_active' => 'required',
                'id_area' => 'required',

            ];
            $messages = [
                'nama_karyawan.required' => 'Nama karyawan wajib diisi',
                'email.required' => 'Email wajib diisi',
                'no_wa.required' => 'No. Whatsapp wajib diisi',
                'id_jabatan.required' => 'Jabatan wajib diisi',
                'is_active.required' => 'Proyek wajib diisi',
                'id_area.required' => 'Area wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $jabatan = Jabatan::where('id', $request->id_jabatan)->first();
            $area = Area::where('id', $request->id_area)->first();

            Karyawan::create([
                'id' => Uuid::uuid4(),
                'nama_karyawan' => $request->nama_karyawan,
                'no_ktp' => $request->no_ktp,
                'no_wa' => $request->no_wa,
                'email' => $request->email,
                'id_jabatan' => $request->id_jabatan,
                'nama_jabatan' => $jabatan->nama_jabatan,
                'is_active' => $request->is_active,
                'id_area' => $request->id_area,
                'nama_area' => $area->nama_area,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'karyawan berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function karyawan_dt()
    {
        $data = Karyawan::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_karyawan="' . $row->nama_karyawan . '"
                                data-no_ktp="' . $row->no_ktp . '"
                                data-no_wa="' . $row->no_wa . '"
                                data-email="' . $row->email . '"
                                data-id_jabatan="' . $row->id_jabatan . '"
                                data-is_active="' . $row->is_active . '"
                                data-id_area="' . $row->id_area . '">
                                <i class="bi bi-pencil"></i></button>';
                $btn = $action . '<button id="btn_delete_datatable" class="btn btn-danger m-1" data-id="' . $row->id . '"><i class="bi bi-trash"></i></button>';
                return $btn;
            })
            ->addColumn('is_active', function ($row) {
                if ($row->is_active == 1) {
                    $aktif = 'AKTIF';
                } else {
                    $aktif = 'NON AKTIF';
                }
                return $aktif;
            })
            ->rawColumns([
                'aksi',
                'is_active'
            ])
            ->make(true);
    }
    public function delete(Request $request)
    {
        try {
            $rules = [
                'id_karyawan_delete' => 'required',

            ];
            $messages = [
                'id_karyawan_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $menu = Karyawan::where('id', $request->id_karyawan_delete)->first();

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'karyawan berhasil disimpan',
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
                'nama_karyawan' => 'required',
                'email' => 'required',
                'no_wa' => 'required',
                'id_jabatan' => 'required',
                'is_active' => 'required',
                'id_area' => 'required',

            ];
            $messages = [
                'nama_karyawan.required' => 'Nama karyawan wajib diisi',
                'email.required' => 'Email wajib diisi',
                'no_wa.required' => 'No. Whatsapp wajib diisi',
                'id_jabatan.required' => 'Jabatan wajib diisi',
                'is_active.required' => 'Proyek wajib diisi',
                'id_area.required' => 'Area wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $jabatan = Jabatan::where('id', $request->id_jabatan)->first();
            $area = Area::where('id', $request->id_area)->first();

            Karyawan::where('id', $request->id_karyawan_update)->update([
                'nama_karyawan' => $request->nama_karyawan,
                'no_ktp' => $request->no_ktp,
                'no_wa' => $request->no_wa,
                'email' => $request->email,
                'id_jabatan' => $request->id_jabatan,
                'nama_jabatan' => $jabatan->nama_jabatan,
                'is_active' => $request->is_active,
                'id_area' => $request->id_area,
                'nama_area' => $area->nama_area,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'karyawan berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
