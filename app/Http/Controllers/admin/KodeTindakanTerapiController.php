<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KodeTindakanTerapi;
use Illuminate\Http\Request;

class KodeTindakanTerapiController extends Controller
{
    public function index()
    {
        $items = KodeTindakanTerapi::all();
        return view('admin.kodentindakan.index', compact('items'));
    }
}
