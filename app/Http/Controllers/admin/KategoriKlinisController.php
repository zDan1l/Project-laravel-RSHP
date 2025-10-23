<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\KategoriKlinis;
use Illuminate\Http\Request;

class KategoriKlinisController extends Controller
{
    public function index()
    {
        $items = KategoriKlinis::all();
        return view('admin.kategoriklinis.index', compact('items'));
    }
}
