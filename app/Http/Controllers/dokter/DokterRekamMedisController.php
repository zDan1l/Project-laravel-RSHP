<?php

namespace App\Http\Controllers\dokter;

use App\Models\Pet;
use App\Models\RekamMedis;
use App\Models\TemuDokter;
use App\Models\DetailRekamMedis;
use App\Models\KodeTindakanTerapi;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class DokterRekamMedisController extends Controller
{
    /**
     * Display antrian pasien untuk dokter yang login
     * Hanya tampilkan pasien yang SUDAH MEMILIKI REKAM MEDIS dari perawat
     * Status: W = Waiting (sudah ada RM, belum ditangani dokter)
     *         P = Process (sedang ditangani dokter)
     *         S = Selesai (untuk riwayat)
     */
    public function antrian(Request $request)
    {
        $idRoleUser = session('idrole_user');
        
        // Ambil TemuDokter yang sudah punya RekamMedis
        $query = TemuDokter::with(['pet.pemilik.user', 'pet.ras.jenisHewan', 'rekamMedis'])
            ->whereHas('rekamMedis'); // HANYA yang sudah ada rekam medis dari perawat

        // Filter by status - default tampilkan yang belum selesai (W, P)
        $statusFilter = $request->input('status', 'active'); // active, all, selesai
        if ($statusFilter == 'active') {
            $query->whereIn('status', ['W', 'P', 'A']); // W, P, atau A yang sudah punya RM
        } elseif ($statusFilter == 'selesai') {
            $query->where('status', 'S');
        }
        // jika 'all', tidak filter status

        // Filter by idrole_user (dokter yang login)
        // TEMPORARY: Comment untuk testing - semua dokter bisa lihat semua pasien
        // if ($idRoleUser) {
        //     $query->where('idrole_user', $idRoleUser);
        // }

        // Filter by date (opsional)
        if ($request->filled('tanggal')) {
            $query->whereDate('waktu_daftar', $request->tanggal);
        }

        $antrian = $query->orderBy('waktu_daftar', 'desc')->get();

        return view('dokter.antrian.index', compact('antrian'));
    }

    /**
     * Lihat rekam medis yang sudah dibuat perawat
     * Dokter akan menambahkan detail tindakan terapi
     */
    public function mulaiPemeriksaan($id)
    {
        try {
            $temuDokter = TemuDokter::findOrFail($id);
            
            // Cari rekam medis yang sudah dibuat perawat
            $rekamMedis = RekamMedis::where('idreservasi_dokter', $id)->first();
            
            if (!$rekamMedis) {
                return redirect()->back()
                    ->with('error', 'Rekam medis belum dibuat oleh perawat. Silakan minta perawat untuk membuat rekam medis terlebih dahulu.');
            }

            // Update status menjadi S (Selesai) setelah dokter selesai
            $temuDokter->update(['status' => 'P']); // P = Proses dokter

            return redirect()->route('dokter.rekam-medis.show', $rekamMedis->idrekam_medis)
                ->with('success', 'Silakan tambahkan detail tindakan terapi untuk pasien ini.');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal membuka rekam medis: ' . $e->getMessage());
        }
    }

    /**
     * Display a listing of rekam medis
     */
    public function index(Request $request)
    {
        $idUser = session('user_id') ?? Auth::id();

        $query = RekamMedis::with([
            'temuDokter.pet.pemilik.user',
            'temuDokter.pet.ras.jenisHewan',
            'dokter',
            'detailRekamMedis.kodeTindakanTerapi'
        ]);

        // Filter by dokter jika user_id ada
        if ($idUser) {
            $query->where('dokter_pemeriksa', $idUser);
        }

        // Filter by search
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->whereHas('temuDokter.pet', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhereHas('temuDokter.pet.pemilik.user', function ($q2) use ($search) {
                    $q2->where('nama', 'like', "%{$search}%");
                })
                ->orWhere('diagnosa', 'like', "%{$search}%");
            });
        }

        // Filter by date range
        if ($request->filled('tanggal_dari')) {
            $query->whereDate('created_at', '>=', $request->tanggal_dari);
        }
        if ($request->filled('tanggal_sampai')) {
            $query->whereDate('created_at', '<=', $request->tanggal_sampai);
        }

        $rekamMedis = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('dokter.rekam-medis.index', compact('rekamMedis'));
    }

    /**
     * Redirect to show - dokter tidak bisa create rekam medis
     * Hanya perawat yang bisa create rekam medis
     */
    public function create($idTemuDokter)
    {
        $rekamMedis = RekamMedis::where('idreservasi_dokter', $idTemuDokter)->first();
        
        if (!$rekamMedis) {
            return redirect()->route('dokter.antrian.index')
                ->with('error', 'Rekam medis belum dibuat oleh perawat.');
        }
        
        return redirect()->route('dokter.rekam-medis.show', $rekamMedis->idrekam_medis);
    }

    /**
     * Dokter tidak bisa store rekam medis
     * Hanya perawat yang bisa membuat rekam medis
     */
    public function store(Request $request)
    {
        return redirect()->route('dokter.rekam-medis.index')
            ->with('error', 'Dokter tidak dapat membuat rekam medis. Hanya perawat yang dapat membuat rekam medis.');
    }

    /**
     * Display the specified rekam medis
     * Dokter bisa lihat dan menambahkan detail tindakan terapi
     */
    public function show($id)
    {
        $rekamMedis = RekamMedis::with([
            'temuDokter.pet.pemilik.user', 
            'temuDokter.pet.ras.jenisHewan', 
            'dokter',
            'detailRekamMedis.kodeTindakanTerapi.kategori',
            'detailRekamMedis.kodeTindakanTerapi.kategoriKlinis'
        ])->findOrFail($id);
        
        $kodeTindakanTerapi = KodeTindakanTerapi::with(['kategori', 'kategoriKlinis'])
            ->orderBy('kode')
            ->get();

        return view('dokter.rekam-medis.show', compact('rekamMedis', 'kodeTindakanTerapi'));
    }

    /**
     * Dokter tidak bisa edit rekam medis utama
     * Hanya perawat yang bisa edit anamnesa, temuan klinis, diagnosa
     */
    public function edit($id)
    {
        return redirect()->route('dokter.rekam-medis.show', $id)
            ->with('error', 'Dokter tidak dapat mengedit data rekam medis utama. Hanya dapat menambahkan detail tindakan terapi.');
    }

    /**
     * Dokter tidak bisa update rekam medis utama
     */
    public function update(Request $request, $id)
    {
        return redirect()->route('dokter.rekam-medis.show', $id)
            ->with('error', 'Dokter tidak dapat mengedit data rekam medis utama.');
    }

    /**
     * Store detail rekam medis (tindakan terapi)
     * Dokter fokus menambahkan detail tindakan terapi
     */
    public function storeDetail(Request $request, $id)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'required|string|max:1000',
        ], [
            'idkode_tindakan_terapi.required' => 'Tindakan terapi harus dipilih',
            'idkode_tindakan_terapi.exists' => 'Tindakan terapi tidak valid',
            'detail.required' => 'Detail rincian harus diisi',
            'detail.max' => 'Detail rincian maksimal 1000 karakter',
        ]);

        $rekamMedis = RekamMedis::findOrFail($id);

        try {
            $maxDetailId = DetailRekamMedis::max('iddetail_rekam_medis') ?? 0;
            
            DetailRekamMedis::create([
                'iddetail_rekam_medis' => $maxDetailId + 1,
                'idrekam_medis' => $id,
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
                'petugas_input' => session('user_id') ?? Auth::id(),
                'tipe_petugas' => 'dokter'
            ]);

            return redirect()->route('dokter.rekam-medis.show', $id)
                ->with('success', 'Tindakan terapi berhasil ditambahkan!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menambah tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Update detail rekam medis
     * Dokter hanya bisa edit tindakan yang dia input sendiri
     */
    public function updateDetail(Request $request, $id, $idDetail)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'required|string|max:1000',
        ], [
            'idkode_tindakan_terapi.required' => 'Tindakan terapi harus dipilih',
            'idkode_tindakan_terapi.exists' => 'Tindakan terapi tidak valid',
            'detail.required' => 'Detail rincian harus diisi',
            'detail.max' => 'Detail rincian maksimal 1000 karakter',
        ]);

        try {
            $detail = DetailRekamMedis::where('iddetail_rekam_medis', $idDetail)->firstOrFail();
            
            // Dokter hanya bisa edit tindakan yang dia input sendiri
            $currentUserId = session('user_id') ?? Auth::id();
            if ($detail->petugas_input && $detail->petugas_input != $currentUserId) {
                return redirect()->route('dokter.rekam-medis.show', $id)
                    ->with('error', 'Anda hanya bisa mengedit tindakan yang Anda input sendiri');
            }
            
            $detail->update([
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
            ]);

            return redirect()->route('dokter.rekam-medis.show', $id)
                ->with('success', 'Tindakan terapi berhasil diperbarui!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal memperbarui tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Delete detail rekam medis
     * Dokter hanya bisa hapus tindakan yang dia input sendiri
     */
    public function deleteDetail($id, $idDetail)
    {
        try {
            $detail = DetailRekamMedis::where('iddetail_rekam_medis', $idDetail)->firstOrFail();
            
            // Dokter hanya bisa hapus tindakan yang dia input sendiri
            $currentUserId = session('user_id') ?? Auth::id();
            if ($detail->petugas_input && $detail->petugas_input != $currentUserId) {
                return redirect()->route('dokter.rekam-medis.show', $id)
                    ->with('error', 'Anda hanya bisa menghapus tindakan yang Anda input sendiri');
            }
            
            $detail->delete();
            
            return redirect()->route('dokter.rekam-medis.show', $id)
                ->with('success', 'Tindakan terapi berhasil dihapus!');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Gagal menghapus tindakan: ' . $e->getMessage());
        }
    }
}
