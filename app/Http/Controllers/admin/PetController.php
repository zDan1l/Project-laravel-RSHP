<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use App\Models\JenisHewan;
use App\Models\Ras;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PetController extends Controller
{
    public function index(){
        $pets = Pet::with(['pemilik.user', 'ras'])->orderBy('idpet', 'desc')->get();
        return view('admin.pet.index', compact('pets'));
    }

    public function create(){
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik', 'desc')->get();
        $rasHewans = Ras::orderBy('nama_ras')->get();
        return view('admin.pet.create', compact('pemiliks', 'rasHewans'));
    }

    public function store(Request $request){
        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'jenis_kelamin' => 'required',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:50',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
        ], [
            'nama.required' => 'Nama pet harus diisi',
            'nama.max' => 'Nama pet maksimal 100 karakter',
            'idras_hewan.required' => 'Ras harus dipilih',
            'idras_hewan.exists' => 'Ras tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus jantan atau betina',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak valid',
        ]);

        try {
            DB::beginTransaction();

            // Generate ID manually since idpet is not auto-increment
            $lastId = Pet::max('idpet');
            $validated['idpet'] = $lastId ? $lastId + 1 : 1;

            Pet::create($validated);

            DB::commit();

            return redirect()
                ->route('admin.pets.index')
                ->with('success', 'Pet berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal menambahkan pet: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $pet = Pet::with(['pemilik.user', 'ras'])->findOrFail($id);
        $pemiliks = Pemilik::with('user')->orderBy('idpemilik', 'desc')->get();
        $rasHewans = Ras::orderBy('nama_ras')->get();
        return view('admin.pet.edit', compact('pet', 'pemiliks', 'rasHewans'));
    }

    public function update(Request $request, $id)
    {
        $pet = Pet::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:100',
            'idras_hewan' => 'required|exists:ras_hewan,idras_hewan',
            'jenis_kelamin' => 'required|in:Jantan,Betina',
            'tanggal_lahir' => 'nullable|date',
            'warna_tanda' => 'nullable|string|max:50',
            'idpemilik' => 'required|exists:pemilik,idpemilik',
        ], [
            'nama.required' => 'Nama pet harus diisi',
            'nama.max' => 'Nama pet maksimal 100 karakter',
            'idras_hewan.required' => 'Ras harus dipilih',
            'idras_hewan.exists' => 'Ras tidak valid',
            'jenis_kelamin.required' => 'Jenis kelamin harus dipilih',
            'jenis_kelamin.in' => 'Jenis kelamin harus Jantan atau Betina',
            'tanggal_lahir.date' => 'Format tanggal lahir tidak valid',
            'idpemilik.required' => 'Pemilik harus dipilih',
            'idpemilik.exists' => 'Pemilik tidak valid',
        ]);

        try {
            DB::beginTransaction();

            $pet->update($validated);

            DB::commit();

            return redirect()
                ->route('admin.pets.index')
                ->with('success', 'Pet berhasil diupdate');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Gagal mengupdate pet: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $pet = Pet::findOrFail($id);
            
            // Check if pet has medical records
            if ($pet->rekamMedis()->exists()) {
                return redirect()
                    ->back()
                    ->with('error', 'Pet tidak dapat dihapus karena memiliki rekam medis');
            }

            DB::beginTransaction();

            $pet->delete();

            DB::commit();

            return redirect()
                ->route('admin.pets.index')
                ->with('success', 'Pet berhasil dihapus');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus pet: ' . $e->getMessage());
        }
    }
}
