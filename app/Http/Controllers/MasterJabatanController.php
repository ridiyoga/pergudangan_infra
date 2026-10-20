<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MasterJabatanController extends Controller
{
    public function index()
    {
        $menu = Menu::orderBy('nama_menu')->get();
        return view('master/jabatan/index', compact('menu'));
    }
    public function create(Request $request)
    {
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

            Jabatan::create([
                'id' => Uuid::uuid4(),
                'nama_jabatan' => $request->nama_jabatan,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jabatan berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function jabatan_dt()
    {
        $data = Jabatan::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_jabatan="' . $row->nama_jabatan . '">
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
                'id_jabatan_delete' => 'required',

            ];
            $messages = [
                'id_jabatan_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $menu = Jabatan::where('id', $request->id_jabatan_delete)->first();

            $menu->delete();

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
