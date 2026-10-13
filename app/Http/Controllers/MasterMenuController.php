<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Ramsey\Uuid\Uuid;
use Yajra\DataTables\Facades\DataTables;

class MasterMenuController extends Controller
{
    public function index()
    {
        $title = 'Home';
        return view('master/menu/index', compact('title'));
    }
    public function create(Request $request)
    {
        try {
            $rules = [
                'nama_menu' => 'required',
                'route' => 'required',
                'icon' => 'required',

            ];
            $messages = [
                'nama_menu.required' => 'Nama Menu wajib diisi',
                'route.required' => 'Route wajib diisi',
                'icon.required' => 'Icon wajib diisi',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            Menu::create([
                'id' => Uuid::uuid4(),
                'nama_menu' => $request->nama_menu,
                'route' => $request->route,
                'icon' => $request->icon,
                'created_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
    public function menu_dt()
    {
        $data = Menu::get();

        return DataTables::of($data)
            ->addColumn('aksi', function ($row) {
                $action = '
                            <button class="btn btn-warning m-1" id="btn_update_datatable"
                                data-id="' . $row->id . '"
                                data-nama_menu="' . $row->nama_menu . '"
                                data-route="' . $row->route . '"
                                data-icon="' . $row->icon . '">
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
                'id_menu_delete' => 'required',

            ];
            $messages = [
                'id_menu_delete.required' => 'Terjadi kesalahan',
            ];
            $validator = Validator::make($request->all(), $rules, $messages);
            if ($validator->fails()) {
                return response()->json([
                    'code' => 400,
                    'errors' => $validator->errors(),
                ]);
            }

            $menu = Menu::where('id', $request->id_menu_delete)->first();

            $menu->delete();

            return response()->json([
                'success' => true,
                'message' => 'Menu berhasil disimpan',
            ], 201);
        } catch (\Exception $e) {

            return $e->getMessage();
        }
    }
}
