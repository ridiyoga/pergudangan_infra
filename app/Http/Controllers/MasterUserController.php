<?php

namespace App\Http\Controllers;

use App\Models\Karyawan;
use App\Models\Menu;
use App\Models\Proyek;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MasterUserController extends Controller
{
    public function index()
    {
        $menu = Menu::orderBy('nama_menu')->get();
        $karyawan = Karyawan::orderBy('nama_karyawan', 'ASC')->get();
        $proyek = Proyek::orderBy('nama_proyek', 'ASC')->get();
        // dd($proyek);
        return view('master/user/index', compact(
            'menu',
            'karyawan',
            'proyek'
        ));
    }
    public function create(Request $request)
    {
        try {
            $rules = [
                'username' => 'required',
                'password' => [
                    'required',
                    'min:8',
                    'regex:/[A-Z]/',
                    'regex:/[a-z]/',
                    'regex:/[0-9]/',
                ],
                'password_confirm' => 'required|same:password',
                'id_proyek' => 'required',
                'id_karyawan' => 'required',
            ];

            $messages = [
                'username.required' => 'Username wajib diisi',

                'password.required' => 'Password wajib diisi',
                'password.min' => 'Password minimal 8 karakter',
                'password.regex' => 'Password harus mengandung huruf besar, huruf kecil, dan angka',

                'password_confirm.required' => 'Konfirmasi Password wajib diisi',
                'password_confirm.same' => 'Konfirmasi Password tidak sama dengan Password',

                'id_proyek.required' => 'Proyek wajib diisi',
                'id_karyawan.required' => 'Karyawan wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $karyawan = Karyawan::where('id', $request->id_karyawan)->first();
            $proyek = Proyek::where('id', $request->id_proyek)->first();

            User::create([
                'id' => Uuid::uuid4()->toString(),
                'id_karyawan' => $request->id_karyawan,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'username' => $request->username,
                'password' => Hash::make($request->password),
                'id_proyek' => $request->id_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function user_dt()
    {
        $data = User::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                        <button class="btn btn-warning m-1" id="btn_update_datatable"
                            data-id="' . $row->id . '"
                            data-id_karyawan="' . $row->id_karyawan . '"
                            data-id_proyek="' . $row->id_proyek . '"
                            data-username="' . $row->username . '">
                            <i class="bi bi-pencil"></i>
                        </button>';

                $btn = $action;

                $btn .= '
                        <button id="btn_password_datatable" class="btn btn-primary m-1"
                            data-id="' . $row->id . '">
                            <i class="bi bi-key"></i>
                        </button>';

                $btn .= '
                        <button id="btn_delete_datatable" class="btn btn-danger m-1"
                        data-id="' . $row->id . '">
                        <i class="bi bi-trash"></i>
                        </button>';

                return $btn;
            })
            ->rawColumns(['aksi'])
            ->make(true);
    }
    public function delete(Request $request)
    {
        try {
            $rules = [
                'id_user_delete' => 'required',

            ];
            $messages = [
                'id_user_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $menu = User::where('id', $request->id_user_delete)->first();

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'User berhasil disimpan',
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
                'username' => 'required',

            ];
            $messages = [
                'username.required' => 'Username wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }
            $karyawan = Karyawan::where('id', $request->id_karyawan)->first();
            $proyek = Proyek::where('id', $request->id_proyek)->first();

            User::where('id', $request->id_user_update)->update([
                'id' => Uuid::uuid4(),
                'id_karyawan' => $request->id_karyawan,
                'nama_karyawan' => $karyawan->nama_karyawan,
                'username' => $request->username,
                'id_proyek' => $request->id_proyek,
                'nama_proyek' => $proyek->nama_proyek,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'User berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
