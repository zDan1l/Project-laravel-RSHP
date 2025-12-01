<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\TemuDokter;
use App\Models\RekamMedis;
use App\Models\DetailRekamMedis;
use App\Models\KodeTindakanTerapi;

class PerawatController extends Controller
{
    /**
     * Dashboard perawat
     */
    public function dashboard()
    {
        // Statistik untuk dashboard
        $totalPasienHariIni = TemuDokter::whereDate('waktu_daftar', today())
            ->whereIn('status', ['A', 'P', 'S'])
            ->count();
        
        $pasienMenunggu = TemuDokter::whereIn('status', ['W', 'A'])
            ->count();
        
        $pasienDiproses = TemuDokter::where('status', 'P')
            ->count();
        
        $pasienSelesai = TemuDokter::whereDate('waktu_daftar', today())
            ->where('status', 'S')
            ->count();

        return view('perawat.dashboard', compact(
            'totalPasienHariIni',
            'pasienMenunggu',
            'pasienDiproses',
            'pasienSelesai'
        ));
    }

    /**
     * Daftar pasien yang perlu didampingi
     */
    public function daftarPasien()
    {
        $pasienList = TemuDokter::with([
            'pet.ras.jenisHewan',
            'pet.pemilik.user',
            'dokter'
        ])
        ->whereIn('status', ['W', 'A', 'P', 'S'])
        ->orderByRaw("FIELD(status, 'P', 'A', 'W', 'S')")
        ->orderBy('waktu_daftar', 'asc')
        ->paginate(15);

        return view('perawat.daftar-pasien.index', compact('pasienList'));
    }

    /**
     * Lihat detail rekam medis pasien (READ ONLY untuk rekam medis utama)
     * Perawat hanya bisa tambah/edit/hapus detail tindakan
     */
    public function lihatRekamMedis($id)
    {
        $rekamMedis = RekamMedis::with([
            'temuDokter.pet.ras.jenisHewan',
            'temuDokter.pet.pemilik.user',
            'dokter',
            'detailRekamMedis.kodeTindakanTerapi'
        ])->findOrFail($id);

        return view('perawat.rekam-medis.show', compact('rekamMedis'));
    }

    /**
     * Tambah tindakan sederhana (injeksi, grooming, perawatan)
     */
    public function tambahTindakan(Request $request, $idRekamMedis)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string'
        ]);

        try {
            // Generate ID untuk detail rekam medis
            $lastDetail = DetailRekamMedis::where('idrekam_medis', $idRekamMedis)
                ->orderBy('iddetail_rekam_medis', 'desc')
                ->first();
            
            if ($lastDetail) {
                $lastNumber = (int) substr($lastDetail->iddetail_rekam_medis, -3);
                $newNumber = str_pad($lastNumber + 1, 3, '0', STR_PAD_LEFT);
            } else {
                $newNumber = '001';
            }
            
            $newId = 'DRM' . $newNumber;

            DetailRekamMedis::create([
                'iddetail_rekam_medis' => $newId,
                'idrekam_medis' => $idRekamMedis,
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail,
                'petugas_input' => session('user_id') ?? Auth::id(),
                'tipe_petugas' => 'perawat'
            ]);

            return redirect()->back()->with('success', 'Tindakan berhasil ditambahkan');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menambahkan tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Update tindakan yang dibuat oleh perawat
     * Perawat hanya bisa edit tindakan yang dia input sendiri
     */
    public function updateTindakan(Request $request, $idRekamMedis, $idDetail)
    {
        $request->validate([
            'idkode_tindakan_terapi' => 'required|exists:kode_tindakan_terapi,idkode_tindakan_terapi',
            'detail' => 'nullable|string'
        ]);

        try {
            $detail = DetailRekamMedis::where('idrekam_medis', $idRekamMedis)
                ->where('iddetail_rekam_medis', $idDetail)
                ->firstOrFail();

            // Perawat hanya bisa edit tindakan yang dia input sendiri
            $currentUserId = session('user_id') ?? Auth::id();
            if ($detail->petugas_input && $detail->petugas_input != $currentUserId) {
                return redirect()->back()->with('error', 'Anda hanya bisa mengedit tindakan yang Anda input sendiri');
            }

            $detail->update([
                'idkode_tindakan_terapi' => $request->idkode_tindakan_terapi,
                'detail' => $request->detail
            ]);

            return redirect()->back()->with('success', 'Tindakan berhasil diperbarui');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal memperbarui tindakan: ' . $e->getMessage());
        }
    }

    /**
     * Hapus tindakan yang dibuat oleh perawat
     * Perawat hanya bisa hapus tindakan yang dia input sendiri
     */
    public function deleteTindakan($idRekamMedis, $idDetail)
    {
        try {
            $detail = DetailRekamMedis::where('idrekam_medis', $idRekamMedis)
                ->where('iddetail_rekam_medis', $idDetail)
                ->firstOrFail();

            // Perawat hanya bisa hapus tindakan yang dia input sendiri
            $currentUserId = session('user_id') ?? Auth::id();
            if ($detail->petugas_input && $detail->petugas_input != $currentUserId) {
                return redirect()->back()->with('error', 'Anda hanya bisa menghapus tindakan yang Anda input sendiri');
            }

            $detail->delete();

            return redirect()->back()->with('success', 'Tindakan berhasil dihapus');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Gagal menghapus tindakan: ' . $e->getMessage());
        }
    }
}
