<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $parent = Menu::orderBy('nama_menu')
            ->where('parent', 1)
            ->with('Children')
            ->orderBy('nama_menu')
            ->get();
        return view('home', compact('parent'));
    }
}
