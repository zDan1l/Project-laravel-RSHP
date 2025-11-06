<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pemilik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PemilikController extends Controller
{
    public function index(){
        $pemiliks = Pemilik::with('user')->get();
        return view('admin.pemilik.index', compact('pemiliks'));
    }

    public function create(){
        $users = User::doesntHave('pemilik')->get();
        return view('admin.pemilik.create', compact('users'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'iduser' => 'required|exists:user,iduser|unique:pemilik,iduser',
            'nama' => 'required|string|max:100',
            'alamat' => 'required|string|max:255',
            'no_telepon' => 'required|string|max:15',
        ], [
            'iduser.required' => 'User harus dipilih',
            'iduser.exists' => 'User tidak valid',
            'iduser.unique' => 'User sudah terdaftar sebagai pemilik',
            'nama.required' => 'Nama harus diisi',
            'nama.max' => 'Nama maksimal 100 karakter',
            'alamat.required' => 'Alamat harus diisi',
            'alamat.max' => 'Alamat maksimal 255 karakter',
            'no_telepon.required' => 'No telepon harus diisi',
            'no_telepon.max' => 'No telepon maksimal 15 karakter',
        ]);

        try {
            DB::beginTransaction();

            Pemilik::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.pemilik.index')
                ->with('success', 'Pemilik berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pemilik: ' . $e->getMessage());
        }
    }
}
