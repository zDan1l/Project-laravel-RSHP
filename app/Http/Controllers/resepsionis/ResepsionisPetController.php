<?php

namespace App\Http\Controllers\resepsionis;

use App\Models\Pet;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ResepsionisPetController extends Controller
{
    public function index(){
        $pets = Pet::with('ras')->get();
        return view('resepsionis.pet.index', compact('pets'));
    }
}
