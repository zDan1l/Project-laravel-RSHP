<?php

namespace App\Http\Controllers\perawat;

use App\Http\Controllers\Controller;
use App\Models\RekamMedis;
use App\Models\DetailRekamMedis;
use App\Models\KodeTindakanTerapi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PerawatRekamMedisController extends Controller
{
    /**
     * Display a listing of rekam medis
     */
    public function index(Request $request)
    {
        $query = RekamMedis::with([
            'temuDokter.pet.pemilik',
            'temuDokter.pet.jenisHewan',
            'dokter'
        ]);

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('temuDokter.pet', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhereHas('temuDokter.pet.pemilik', function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%");
            })
            ->orWhere('diagnosa', 'like', "%{$search}%");
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $rekamMedis = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('perawat.rekam-medis.index', compact('rekamMedis'));
    }

    /**
     * Display the specified rekam medis with details
     */
    public function show($id)
    {
        $rekamMedis = RekamMedis::with([
            'temuDokter.pet.pemilik',
            'temuDokter.pet.jenisHewan',
            'dokter',
            'detailRekamMedis.kodeTindakanTerapi.kategori',
            'detailRekamMedis.kodeTindakanTerapi.kategoriKlinis'
        ])->findOrFail($id);

        $kodeTindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])
            ->orderBy('kode')
            ->get();

        return view('perawat.rekam-medis.show', compact('rekamMedis', 'kodeTindakanTerapi'));
    }

    /**
     * Store detail tindakan terapi
     */
    public function storeDetail(Request $request, $idRekamMedis)
    {
        $validated = $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'required|string|max:1000',
        ], [
            'idkode_tindakan_terapi.required' => 'Tindakan terapi harus dipilih',
            'idkode_tindakan_terapi.exists' => 'Tindakan terapi tidak valid',
            'detail.required' => 'Detail rincian harus diisi',
            'detail.max' => 'Detail rincian maksimal 1000 karakter',
        ]);

        try {
            DB::beginTransaction();

            DetailRekamMedis::create([
                'idrekam_medis' => $idRekamMedis,
                'idkode_tindakan_terapi' => $validated['idkode_tindakan_terapi'],
                'detail' => $validated['detail'],
            ]);

            DB::commit();

            return redirect()
                ->route('perawat.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Detail tindakan terapi berhasil ditambahkan');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('error', 'Gagal menambahkan detail tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Update detail tindakan terapi
     */
    public function updateDetail(Request $request, $idRekamMedis, $idDetail)
    {
        $validated = $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'required|string|max:1000',
        ]);

        try {
            $detailRekamMedis = DetailRekamMedis::where('idrekam_medis', $idRekamMedis)
                ->where('iddetail_rekam_medis', $idDetail)
                ->firstOrFail();

            $detailRekamMedis->update($validated);

            return redirect()
                ->route('perawat.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Detail tindakan terapi berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal memperbarui detail tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Delete detail tindakan terapi
     */
    public function deleteDetail($idRekamMedis, $idDetail)
    {
        try {
            $detailRekamMedis = DetailRekamMedis::where('idrekam_medis', $idRekamMedis)
                ->where('iddetail_rekam_medis', $idDetail)
                ->firstOrFail();

            $detailRekamMedis->delete();

            return redirect()
                ->route('perawat.rekam-medis.show', $idRekamMedis)
                ->with('success', 'Detail tindakan terapi berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->with('error', 'Gagal menghapus detail tindakan: ' . $e->getMessage());
        }
    }
}
