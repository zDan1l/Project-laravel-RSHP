<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    public function index(){
        return view('login');
    }

    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email'],
            'password' => ['required']
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $user = User::with(['userRole' => function($query){
            $query->where('status', 1);
        }, 'userRole.role'])
        ->where('email', $request->input('email'))
        ->first();

        if (!$user) {
            return redirect()->back()
            ->withErrors(['email' => 'Email tidak ditemukan'])
            ->withInput();
        }

        if(!Hash::check($request->password, $user->password)){
            return redirect()->back()
            ->withErrors(['password' => 'Password salah'])
            ->withInput();
        }

        $namaRole = Role::where('idrole', $user->userRole[0]->idrole ?? null)->first();

        Auth::login($user);

        $request->session()->put([
            'user_id' => $user->iduser,
            'user_name' => $user->nama,
            'user_email' => $user->email,
            'user_role' => $user->userRole[0]->idrole ?? 'user',
            'user_role_name' => $namaRole->nama_role ?? 'user',
            'user_status' => $user->userRole[0]->status ?? 'active'
        ]);
        
        return redirect()->intended('/admin/dashboard')->with('success', 'Login berhasil');

    }

    public function logout(Request $request){
        Auth::logout();
        
        $request->session->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Logout berhasil');
    }
}
