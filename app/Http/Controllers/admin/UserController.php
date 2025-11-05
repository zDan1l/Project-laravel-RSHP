<?php

namespace App\Http\Controllers\admin;

use App\Models\User;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public function index(){
        $users = User::with('userRole')->get();
        // dd($users);
        return view('admin.user.index', compact('users'));
    }
}
