<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Area;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MasterAreaController extends Controller
{
    public function index()
    {
        $menu = Menu::orderBy('nama_menu')->get();
        return view('master/area/index', compact('menu'));
    }
    public function create(Request $request)
    {
        try {
            $rules = [
                'nama_area' => 'required',

            ];
            $messages = [
                'nama_area.required' => 'Nama Area wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            Area::create([
                'id' => Uuid::uuid4(),
                'nama_area' => $request->nama_area,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Area berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function area_dt()
    {
        $data = Area::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_area="' . $row->nama_area . '">
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
                'id_area_delete' => 'required',

            ];
            $messages = [
                'id_area_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $menu = Area::where('id', $request->id_area_delete)->first();

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Area berhasil disimpan',
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
                'nama_area' => 'required',

            ];
            $messages = [
                'nama_area.required' => 'Nama Area wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            Area::where('id', $request->id_area_update)->update([
                'nama_area' => $request->nama_area,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Area berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
