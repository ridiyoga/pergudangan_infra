<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Log;
use Yajra\DataTables\Facades\DataTables;

class MasterLogController extends Controller
{
    public function index($id)
    {
        $parent = Menu::orderBy('nama_menu')
            ->where('parent', 1)
            ->with('Children')
            ->orderBy('nama_menu')
            ->get();
        return view('log', compact('parent'));
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
