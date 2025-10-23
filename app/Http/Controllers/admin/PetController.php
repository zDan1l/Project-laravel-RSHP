<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use Illuminate\Http\Request;

class PetController extends Controller
{
    public function index(){
        $pets = Pet::with('ras')->get();

        return view('admin.pet.index', compact('pets'));
    }
}
