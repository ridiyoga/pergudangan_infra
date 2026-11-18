<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Jabatan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MasterHakAksesController extends Controller
{
    public function index($id)
    {
        $parent = Menu::orderBy('nama_menu')
            ->where('parent', 1)
            ->with('Children')
            ->orderBy('nama_menu')
            ->get();
        // dd($parent);
        return view('master/hak_akses/index', compact('parent'));
    }

    public function hak_akses_dt()
    {
        $data = Jabatan::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_jabatan="' . $row->nama_jabatan . '">
                                <i class="bi bi-pencil"></i></button>';
                return $action;
            })
            ->rawColumns(['aksi'])
            ->make(true);
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
