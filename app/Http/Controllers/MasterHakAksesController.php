<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Jabatan;
use App\Models\HakAkses;
use App\Models\Karyawan;
use App\Models\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MasterHakAksesController extends Controller
{
    public function index($id)
    {
        $user = Auth::user();
        $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
        $parent = HakAkses::where('id_jabatan', $karyawan->id_jabatan)
            ->with('Menu.Children')
            ->orderBy('nama_menu')
            ->get();
        // dd($parent);
        return view('master/hak_akses/index', compact('parent'));
    }

    public function hak_akses_dt()
    {
        $data = Jabatan::with('HakAkses')->get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_jabatan="' . $row->nama_jabatan . '">
                                <i class="bi bi-pencil"></i></button>';
                return $action;
            })
            ->addColumn('menu', function ($row) {
                if ($row->HakAkses->isEmpty()) {
                    return '-';
                }

                return '• ' . $row->HakAkses->pluck('nama_menu')->implode('<br>• ');
            })
            ->rawColumns([
                'aksi',
                'menu'
            ])
            ->make(true);
    }
    public function hak_akses_menu_dt(Request $request)
    {
        $data = Menu::where('parent', 1)->get();

        $akses = HakAkses::where(
            'id_jabatan',
            $request->id_jabatan
        )->pluck('id_menu')->toArray();

        return DataTables::of($data)

            ->addColumn('check', function ($row) use ($akses) {

                $checked = in_array($row->id, $akses) ? 'checked' : '';

                return '
                <div class="form-check">
                    <input class="form-check-input"
                        type="checkbox"
                        name="id_menu[]"
                        value="' . $row->id . '"
                        ' . $checked . '>
                </div>
            ';
            })
            ->rawColumns([
                'check',
            ])
            ->make(true);
    }
    public function create(Request $request)
    {
        // dd($request->all());
        // dd($user->id_karyawan);
        $rules = [
            'id_jabatan' => 'required|uuid|exists:master_jabatan,id',
            'nama_jabatan' => 'required',
            'id_menu' => 'nullable|array',
            'id_menu.*' => 'required|uuid|exists:master_menu,id',
        ];

        $messages = [
            'id_jabatan.required' => 'ID Jabatan wajib diisi',
            'id_jabatan.uuid' => 'Format ID Jabatan tidak valid',
            'id_jabatan.exists' => 'Jabatan tidak ditemukan',

            'nama_jabatan.required' => 'Nama Jabatan wajib diisi',

            'id_menu.array' => 'Format menu tidak valid',
            'id_menu.*.required' => 'ID Menu wajib diisi',
            'id_menu.*.uuid' => 'Format ID Menu tidak valid',
            'id_menu.*.exists' => 'Menu tidak ditemukan',
        ];
        $validator = Validator::make($request->all(), $rules, $messages);

        if ($validator->fails()) {
            return response()->json([
                'code' => 400,
                'errors' => $validator->errors(),
            ]);
        }
        try {
            $jabatan = Jabatan::where('id', $request->id_jabatan)->first();
            HakAkses::where('id_jabatan', $request->id_jabatan)->delete();
            foreach ($request->id_menu ?? [] as $id_menu) {
                $nama_menu = Menu::where('id', $id_menu)->first();
                HakAkses::create([
                    'id' => Uuid::uuid4(),
                    'id_jabatan' => $request->id_jabatan,
                    'nama_jabatan' => $jabatan->nama_jabatan,
                    'id_menu' => $id_menu,
                    'nama_menu' => $nama_menu->nama_menu,
                ]);
            }
            $user = Auth::user();
            $karyawan = Karyawan::where('id', $user->id_karyawan)->first();
            Log::create([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $karyawan->id,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'aksi' => 'UPDATE',
                'deskripsi' => 'Memperbaharui hak akses pada jabatan ' . $jabatan->nama_jabatan,
                'id_menu' => null,
                'nama_menu' => 'Hak Akses',
                'id_tabel_baru' => null,
                'nama_tabel' => 'master_hak_akses',
                'id_tabel_lama' => null,
                'keterangan' => 'success',
            ]);
            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil disimpan',
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
                'nama_jabatan' => 'required',

            ];
            $messages = [
                'nama_jabatan.required' => 'Nama Jabatan wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            Jabatan::where('id', $request->id_jabatan_update)->update([
                'nama_jabatan' => $request->nama_jabatan,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
