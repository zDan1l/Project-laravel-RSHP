<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index(){
        return view('home');
    }

    public function tentang(){
        return view('tentang');
    }

    public function layanan(){
        return view('layanan');
    }

    public function struktur(){
        return view('struktur');
    }

    public function kontak(){
        return view('kontak');
    }
}
