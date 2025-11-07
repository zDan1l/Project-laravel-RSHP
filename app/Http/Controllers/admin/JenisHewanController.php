<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\JenisHewan;
use App\Helpers\StringHelper;
use App\Helpers\ValidationHelper;
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

        // Additional validation using helper
        $validationResult = ValidationHelper::validateNamaJenisHewan($validated['nama_jenis']);
        if (!$validationResult['valid']) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $validationResult['message']);
        }

        try {
            DB::beginTransaction();

            // Format nama jenis hewan sebelum menyimpan
            $validated['nama_jenis'] = $this->formatNamaJenisHewan($validated['nama_jenis']);

            // Create using private function
            $jenisHewan = $this->createJenisHewan($validated);

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

        // Additional validation using helper
        $validationResult = ValidationHelper::validateNamaJenisHewan($validated['nama_jenis']);
        if (!$validationResult['valid']) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', $validationResult['message']);
        }

        try {
            DB::beginTransaction();

            // Format nama jenis hewan sebelum update
            $validated['nama_jenis'] = $this->formatNamaJenisHewan($validated['nama_jenis']);

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

    /**
     * Private function untuk membuat jenis hewan baru
     * 
     * @param array $data
     * @return JenisHewan
     */
    private function createJenisHewan(array $data)
    {
        // Sanitize data sebelum create
        $sanitizedData = ValidationHelper::sanitizeInput($data);
        
        // Create dan return model
        return JenisHewan::create($sanitizedData);
    }

    /**
     * Private function untuk format nama jenis hewan
     * Menggunakan helper StringHelper
     * 
     * @param string $namaJenis
     * @return string
     */
    private function formatNamaJenisHewan($namaJenis)
    {
        // Gunakan helper untuk format
        return StringHelper::formatNamaJenisHewan($namaJenis);
    }
}
