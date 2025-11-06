<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JenisHewan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class JenisHewanController extends Controller
{
    public function index()
    {
        $items = JenisHewan::all();
        return view('admin.jenishewan.index', compact('items'));
    }

    public function create(){
        return view('admin.jenishewan.create');
    }

    public function store(Request $request){
        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100',
        ], [
            'nama_jenis.required' => 'Nama jenis hewan harus diisi',
            'nama_jenis.max' => 'Nama jenis hewan maksimal 100 karakter',
        ]);

        try {
            DB::beginTransaction();

            JenisHewan::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.jenis-hewan.index')
                ->with('success', 'Jenis hewan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan jenis hewan: ' . $e->getMessage());
        }
    }
}
