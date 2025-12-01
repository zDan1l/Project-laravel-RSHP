<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JenisHewan;
use App\Models\Ras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RasController extends Controller
{
    public function index()
    {
        $items = Ras::with('jenisHewan')->orderBy('idras_hewan', 'desc')->get();
        return view('admin.ras.index', compact('items'));
    }

    public function create()
    {
        $jenisHewans = JenisHewan::orderBy('nama_jenis_hewan', 'asc')->get();
        return view('admin.ras.create', compact('jenisHewans'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'nama_ras' => 'required|string|max:100',
        ], [
            'idjenis_hewan.required' => 'Jenis hewan harus dipilih',
            'idjenis_hewan.exists' => 'Jenis hewan tidak valid',
            'nama_ras.required' => 'Nama ras harus diisi',
            'nama_ras.max' => 'Nama ras maksimal 100 karakter',
        ]);

        try {
            DB::beginTransaction();

            // Generate ID manually since idras_hewan is not auto-increment
            $lastId = Ras::max('idras_hewan');
            $validated['idras_hewan'] = $lastId ? $lastId + 1 : 1;

            Ras::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.ras.index')
                ->with('success', 'Ras hewan berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan ras hewan: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $item = Ras::findOrFail($id);
        $jenisHewans = JenisHewan::orderBy('nama_jenis_hewan', 'asc')->get();
        return view('admin.ras.edit', compact('item', 'jenisHewans'));
    }

    public function update(Request $request, $id)
    {
        $item = Ras::findOrFail($id);

        $validated = $request->validate([
            'idjenis_hewan' => 'required|exists:jenis_hewan,idjenis_hewan',
            'nama_ras' => 'required|string|max:100',
        ], [
            'idjenis_hewan.required' => 'Jenis hewan harus dipilih',
            'idjenis_hewan.exists' => 'Jenis hewan tidak valid',
            'nama_ras.required' => 'Nama ras harus diisi',
            'nama_ras.max' => 'Nama ras maksimal 100 karakter',
        ]);

        try {
            DB::beginTransaction();

            $item->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.ras.index')
                ->with('success', 'Ras hewan berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate ras hewan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $item = Ras::findOrFail($id);

            // Check if being used by pets
            if ($item->pet()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Ras hewan tidak dapat dihapus karena sedang digunakan oleh pet');
            }

            DB::beginTransaction();

            $item->delete();

            DB::commit();

            return redirect()
                ->route('admin.ras.index')
                ->with('success', 'Ras hewan berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus ras hewan: ' . $e->getMessage());
        }
    }
}
