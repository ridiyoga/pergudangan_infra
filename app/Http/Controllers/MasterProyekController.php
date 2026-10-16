<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Proyek;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MasterProyekController extends Controller
{
    public function index()
    {
        $menu = Menu::orderBy('nama_menu')->get();
        return view('master/proyek/index', compact('menu'));
    }
    public function create(Request $request)
    {
        try {
            $rules = [
                'nama_proyek' => 'required',
                'akronim' => 'required',

            ];
            $messages = [
                'nama_proyek.required' => 'Nama Proyek wajib diisi',
                'akronim.required' => 'Akronim wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            Proyek::create([
                'id' => Uuid::uuid4(),
                'nama_proyek' => $request->nama_proyek,
                'akronim' => $request->akronim,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function proyek_dt()
    {
        $data = Proyek::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_proyek="' . $row->nama_proyek . '"
                                data-akronim="' . $row->akronim . '">
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
                'id_proyek_delete' => 'required',

            ];
            $messages = [
                'id_proyek_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $menu = Proyek::where('id', $request->id_proyek_delete)->first();

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil disimpan',
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
                'nama_proyek' => 'required',
                'akronim' => 'required',

            ];
            $messages = [
                'nama_proyek.required' => 'Nama Proyek wajib diisi',
                'akronim.required' => 'Akronim wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            Proyek::where('id', $request->id_proyek_update)->update([
                'id' => Uuid::uuid4(),
                'nama_proyek' => $request->nama_proyek,
                'akronim' => $request->akronim,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Proyek berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
