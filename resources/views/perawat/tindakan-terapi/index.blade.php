@extends('layouts.perawat')

@section('title', 'Tindakan Terapi')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Tindakan Terapi</h4>
                    <p class="text-muted mb-0">Kelola tindakan terapi untuk pasien hewan</p>
                </div>
                <a href="{{ route('perawat.tindakan-terapi.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-2"></i>Tambah Tindakan
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fas fa-exclamation-circle me-2"></i>{{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    @endif

    <!-- Filter Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <form action="" method="GET" class="row g-3">
                            <div class="col-md-4">
                                <label for="search" class="form-label">Cari</label>
                                <input type="text" class="form-control" id="search" name="search" 
                                       value="{{ request('search') }}"
                                       placeholder="Kode atau deskripsi tindakan...">
                            </div>
                            <div class="col-md-3">
                                <label for="kategori" class="form-label">Kategori</label>
                                <select class="form-select" id="kategori" name="kategori">
                                    <option value="">Semua Kategori</option>
                                    @foreach($kategoriList as $kat)
                                        <option value="{{ $kat->idkategori }}" {{ request('kategori') == $kat->idkategori ? 'selected' : '' }}>
                                            {{ $kat->nama_kategori }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="kategori_klinis" class="form-label">Kategori Klinis</label>
                                <select class="form-select" id="kategori_klinis" name="kategori_klinis">
                                    <option value="">Semua</option>
                                    @foreach($kategoriKlinisList as $katKlinis)
                                        <option value="{{ $katKlinis->idkategori_klinis }}" {{ request('kategori_klinis') == $katKlinis->idkategori_klinis ? 'selected' : '' }}>
                                            {{ $katKlinis->nama_kategori_klinis }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Table Card -->
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Daftar Tindakan Terapi</h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th width="5%">No</th>
                                        <th width="10%">Kode</th>
                                        <th width="35%">Deskripsi Tindakan</th>
                                        <th width="15%">Kategori</th>
                                        <th width="15%">Kategori Klinis</th>
                                        <th width="10%" class="text-center">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($tindakanTerapi as $index => $tindakan)
                                    <tr>
                                        <td>{{ $tindakanTerapi->firstItem() + $index }}</td>
                                        <td><span class="badge bg-primary">{{ $tindakan->kode }}</span></td>
                                        <td>{{ Str::limit($tindakan->deskripsi_tindakan_terapi, 50) }}</td>
                                        <td>{{ $tindakan->kategori->nama_kategori ?? '-' }}</td>
                                        <td>{{ $tindakan->kategoriKlinis->nama_kategori_klinis ?? '-' }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('perawat.tindakan-terapi.show', $tindakan->idkode_tindakan_terapi) }}" 
                                               class="btn btn-sm btn-info text-white me-1" title="Lihat">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <a href="{{ route('perawat.tindakan-terapi.edit', $tindakan->idkode_tindakan_terapi) }}" 
                                               class="btn btn-sm btn-warning text-white me-1" title="Edit">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="{{ route('perawat.tindakan-terapi.destroy', $tindakan->idkode_tindakan_terapi) }}" 
                                                  method="POST" 
                                                  class="d-inline"
                                                  onsubmit="return confirm('Apakah Anda yakin ingin menghapus tindakan terapi ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="text-center py-4">
                                            <i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
                                            <p class="text-muted mb-0">Belum ada data tindakan terapi</p>
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        @if($tindakanTerapi->hasPages())
                        <div class="card-footer">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="text-muted small">
                                    Menampilkan {{ $tindakanTerapi->firstItem() }} - {{ $tindakanTerapi->lastItem() }} dari {{ $tindakanTerapi->total() }} data
                                </div>
                                <div>
                                    {{ $tindakanTerapi->links() }}
                                </div>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
