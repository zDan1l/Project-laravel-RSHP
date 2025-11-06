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
        return view('admin.jenis-hewan.index', compact('items'));
    }

    public function create(){
        return view('admin.jenis-hewan.create');
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

    public function edit($id)
    {
        $item = JenisHewan::findOrFail($id);
        return view('admin.jenis-hewan.edit', compact('item'));
    }

    public function update(Request $request, $id)
    {
        $item = JenisHewan::findOrFail($id);

        $validated = $request->validate([
            'nama_jenis' => 'required|string|max:100',
        ], [
            'nama_jenis.required' => 'Nama jenis hewan harus diisi',
            'nama_jenis.max' => 'Nama jenis hewan maksimal 100 karakter',
        ]);

        try {
            DB::beginTransaction();

            $item->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.jenis-hewan.index')
                ->with('success', 'Jenis hewan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate jenis hewan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $item = JenisHewan::findOrFail($id);
            
            // Check if being used by pets
            if ($item->pets()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Jenis hewan tidak dapat dihapus karena sedang digunakan oleh pet');
            }

            $item->delete();

            return redirect()
                ->route('admin.jenis-hewan.index')
                ->with('success', 'Jenis hewan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus jenis hewan: ' . $e->getMessage());
        }
    }
}
