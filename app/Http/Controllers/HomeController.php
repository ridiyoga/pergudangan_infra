<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    //
    public function index()
    {
        $menu = Menu::orderBy('nama_menu')->get();
        return view('home', compact('menu'));
    }
}
