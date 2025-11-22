<?php

namespace App\Http\Controllers\resepsionis;

use App\Http\Controllers\Controller;
use App\Models\Pet;
use App\Models\Pemilik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ResepsionisDashboardController extends Controller
{
    public function index()
    {
        // Set locale untuk Carbon ke Indonesia
        Carbon::setLocale('id');
        
        // Get statistics
        $stats = $this->getStatistics();
        
        // Tanggal hari ini dalam bahasa Indonesia
        $tanggalHariIni = Carbon::now()->isoFormat('dddd, D MMMM YYYY');
        
        return view('resepsionis.dashboard.index', compact('stats', 'tanggalHariIni'));
    }
    
    /**
     * Get dashboard statistics
     */
    private function getStatistics()
    {
        $today = Carbon::today();
        $thisMonth = Carbon::now()->startOfMonth();
        
        return [
            // Janji temu hari ini (dummy - akan diimplementasi saat ada tabel janji temu)
            'janji_hari_ini' => 0, // TODO: Implement when temu_dokter table ready
            
            // Pasien (Pet) baru bulan ini (dummy data karena tabel pet tidak punya created_at)
            'pasien_baru_bulan_ini' => 0, // TODO: Tambah kolom created_at atau tracking terpisah
            
            // Total pasien terdaftar
            'total_pasien' => Pet::count(),
            
            // Total pemilik terdaftar
            'total_pemilik' => Pemilik::count(),
            
            // Pemilik baru bulan ini (dummy data karena tabel pemilik tidak punya created_at)
            'pemilik_baru_bulan_ini' => 0, // TODO: Tambah kolom created_at atau tracking terpisah
        ];
    }
}
