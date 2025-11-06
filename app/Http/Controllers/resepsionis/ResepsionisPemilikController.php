<?php

namespace App\Http\Controllers\resepsionis;

use App\Models\Pemilik;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResepsionisPemilikController extends Controller
{
    public function index(){
        $pemiliks = Pemilik::with('user')->get();
        return view('resepsionis.pemilik.index', compact('pemiliks'));
    }
}
