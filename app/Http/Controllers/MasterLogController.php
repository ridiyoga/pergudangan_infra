<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Log;
use Yajra\DataTables\Facades\DataTables;

class MasterLogController extends Controller
{
    public function index($id)
    {
        $menu = Menu::orderBy('nama_menu')->get();
        $menu_id = Menu::where('id', $id)->first();
        return view('log', compact('menu', 'menu_id'));
    }
    public function log_dt()
    {
        $data = Log::query();

        return DataTables::of($data)
            ->addColumn('created_at', function ($row) {
                return $row->created_at->translatedFormat('d-m-y, H:i:s');
            })

            ->make(true);
    }
}
