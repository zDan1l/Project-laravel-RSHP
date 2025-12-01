@extends('layouts.perawat')

@section('title', 'Detail Rekam Medis')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h4 class="mb-1">Detail Rekam Medis</h4>
                    <p class="text-muted mb-0">Informasi lengkap rekam medis pasien</p>
                </div>
                <a href="{{ route('perawat.daftar-pasien.index') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-2"></i>Kembali
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

    <div class="row">
        <!-- Main Information -->
        <div class="col-lg-8">
            <!-- Rekam Medis Info - READ ONLY -->
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">
                        <i class="fas fa-file-medical me-2"></i>Informasi Rekam Medis
                        <span class="badge bg-light text-dark ms-2">Read Only</span>
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <small class="text-muted d-block">ID Rekam Medis</small>
                            <strong>{{ $rekamMedis->idrekam_medis }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">No. Reservasi</small>
                            <strong>#{{ str_pad($rekamMedis->temuDokter->no_urut ?? '-', 3, '0', STR_PAD_LEFT) }}</strong>
                        </div>
                        <div class="col-md-4">
                            <small class="text-muted d-block">Tanggal Pemeriksaan</small>
                            <strong>{{ $rekamMedis->created_at ? $rekamMedis->created_at->format('d F Y, H:i') : '-' }}</strong>
                        </div>
                    </div>

                    <hr>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Anamnesa</label>
                        <p class="border rounded p-3 bg-light mb-0">
                            {{ $rekamMedis->anamnesa ?? 'Tidak ada catatan' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Temuan Klinis</label>
                        <p class="border rounded p-3 bg-light mb-0">
                            {{ $rekamMedis->temuan_klinis ?? 'Tidak ada catatan' }}
                        </p>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Diagnosa</label>
                        <p class="border rounded p-3 bg-light mb-0">
                            {{ $rekamMedis->diagnosa ?? 'Tidak ada diagnosa' }}
                        </p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <small class="text-muted d-block">Dokter Pemeriksa</small>
                            <strong>{{ $rekamMedis->dokter->nama ?? 'Tidak diketahui' }}</strong>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Detail Tindakan Terapi - CUD ALLOWED -->
            <div class="card mb-4">
                <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
                    <h5 class="mb-0"><i class="fas fa-syringe me-2"></i>Detail Tindakan Terapi</h5>
                    <button class="btn btn-light btn-sm" data-bs-toggle="modal" data-bs-target="#addTindakanModal">
                        <i class="fas fa-plus me-1"></i>Tambah Tindakan
                    </button>
                </div>
                <div class="card-body">
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle me-2"></i>
                        <strong>Catatan:</strong> Anda dapat menambahkan tindakan sederhana. Anda hanya dapat mengedit/menghapus tindakan yang Anda input sendiri.
                    </div>

                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                                <th width="5%">No</th>
                                                <th width="12%">Kode</th>
                                                <th width="25%">Tindakan Terapi</th>
                                                <th width="28%">Detail Rincian</th>
                                                <th width="15%">Input Oleh</th>
                                                <th width="15%" class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse($rekamMedis->detailRekamMedis as $index => $detail)
                                            <tr>
                                                <td>{{ $index + 1 }}</td>
                                                <td><span class="badge bg-primary">{{ $detail->kodeTindakanTerapi->kode ?? '-' }}</span></td>
                                                <td>{{ $detail->kodeTindakanTerapi->deskripsi_tindakan_terapi ?? '-' }}</td>
                                                <td>{{ $detail->detail ?? '-' }}</td>
                                                <td>
                                                    @if($detail->tipe_petugas == 'perawat')
                                                        <span class="badge bg-success">Perawat</span>
                                                    @else
                                                        <span class="badge bg-info">Dokter</span>
                                                    @endif
                                                </td>
                                                <td class="text-center">
                                                    @php
                                                        $currentUserId = session('user_id');
                                                        $canEdit = ($detail->petugas_input == $currentUserId);
                                                    @endphp
                                                    
                                                    @if($canEdit)
                                                        <button class="btn btn-sm btn-warning text-white me-1" 
                                                                data-bs-toggle="modal" 
                                                                data-bs-target="#editTindakanModal{{ $detail->iddetail_rekam_medis }}"
                                                                title="Edit">
                                                            <i class="fas fa-edit"></i>
                                                        </button>
                                                        <form action="{{ route('perawat.rekam-medis.tindakan.delete', [$rekamMedis->idrekam_medis, $detail->iddetail_rekam_medis]) }}" 
                                                              method="POST" 
                                                              class="d-inline"
                                                              onsubmit="return confirm('Apakah Anda yakin ingin menghapus detail tindakan ini?')">
                                                            @csrf
                                                            @method('DELETE')
                                                            <button type="submit" class="btn btn-sm btn-danger" title="Hapus">
                                                                <i class="fas fa-trash"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <span class="badge bg-secondary" title="Anda hanya dapat mengedit tindakan yang Anda input sendiri">
                                                            <i class="fas fa-lock"></i>
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="6" class="text-center py-4">
                                                    <i class="fas fa-inbox fa-2x text-muted mb-2 d-block"></i>
                                                    <p class="text-muted mb-0">Belum ada tindakan terapi yang dicatat</p>
                                                </td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Sidebar -->
                    <div class="col-lg-4">
                        <!-- Pet Info -->
                        <div class="card mb-4">
                            <div class="card-header bg-info text-white">
                                <h6 class="mb-0"><i class="fas fa-paw me-2"></i>Informasi Pet</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Nama Pet</small>
                                    <strong class="fs-5">{{ $rekamMedis->temuDokter->pet->nama ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Ras</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->ras->nama_ras ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Jenis Hewan</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->ras->jenisHewan->nama_jenis_hewan ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Jenis Kelamin</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->jenis_kelamin == 'L' ? 'Jantan' : 'Betina' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">Tanggal Lahir</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->tanggal_lahir ?? '-' }}</strong>
                                </div>
                                <div class="mb-0">
                                    <small class="text-muted d-block">Warna/Tanda</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->warna_tanda ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>

                        <!-- Owner Info -->
                        <div class="card">
                            <div class="card-header bg-secondary text-white">
                                <h6 class="mb-0"><i class="fas fa-user me-2"></i>Informasi Pemilik</h6>
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <small class="text-muted d-block">Nama Pemilik</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->pemilik->user->nama ?? '-' }}</strong>
                                </div>
                                <div class="mb-3">
                                    <small class="text-muted d-block">No. WhatsApp</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->pemilik->no_wa ?? '-' }}</strong>
                                </div>
                                <div class="mb-0">
                                    <small class="text-muted d-block">Alamat</small>
                                    <strong>{{ $rekamMedis->temuDokter->pet->pemilik->alamat ?? '-' }}</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah Tindakan -->
    <div class="modal fade" id="addTindakanModal" tabindex="-1" aria-labelledby="addTindakanModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTindakanModalLabel">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Tindakan Terapi
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="{{ route('perawat.rekam-medis.tindakan.store', $rekamMedis->idrekam_medis) }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            Tambahkan tindakan sederhana seperti injeksi, grooming, atau perawatan lainnya.
                        </div>

                        <div class="mb-3">
                            <label for="idkode_tindakan_terapi" class="form-label">
                                Tindakan Terapi <span class="text-danger">*</span>
                            </label>
                            <select class="form-select @error('idkode_tindakan_terapi') is-invalid @enderror" 
                                    id="idkode_tindakan_terapi" 
                                    name="idkode_tindakan_terapi" required>
                                <option value="">-- Pilih Tindakan Terapi --</option>
                                @foreach(\App\Models\KodeTindakanTerapi::orderBy('kode')->get() as $tindakan)
                                    <option value="{{ $tindakan->idkode_tindakan_terapi }}">
                                        {{ $tindakan->kode }} - {{ $tindakan->deskripsi_tindakan_terapi }}
                                    </option>
                                @endforeach
                            </select>
                            @error('idkode_tindakan_terapi')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="detail" class="form-label">Detail/Catatan</label>
                            <textarea class="form-control @error('detail') is-invalid @enderror" 
                                      id="detail" name="detail" rows="3" 
                                      placeholder="Catatan tambahan tindakan...">{{ old('detail') }}</textarea>
                            <small class="text-muted">Opsional - tambahkan detail spesifik tindakan jika diperlukan</small>
                            @error('detail')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="alert alert-info mb-0">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Informasi:</strong> Detail tindakan akan ditambahkan ke rekam medis pasien ini.
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Simpan Tindakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Edit Tindakan (hanya untuk tindakan yang diinput perawat sendiri) -->
    @foreach($rekamMedis->detailRekamMedis as $detail)
        @php
            $currentUserId = session('user_id');
            $canEdit = ($detail->petugas_input == $currentUserId);
        @endphp
        
        @if($canEdit)
        <div class="modal fade" id="editTindakanModal{{ $detail->iddetail_rekam_medis }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="fas fa-edit me-2"></i>Edit Tindakan Terapi
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form action="{{ route('perawat.rekam-medis.tindakan.update', [$rekamMedis->idrekam_medis, $detail->iddetail_rekam_medis]) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="idkode_tindakan_terapi_edit{{ $detail->iddetail_rekam_medis }}" class="form-label">
                                    Tindakan Terapi <span class="text-danger">*</span>
                                </label>
                                <select class="form-select" 
                                        id="idkode_tindakan_terapi_edit{{ $detail->iddetail_rekam_medis }}" 
                                        name="idkode_tindakan_terapi" required>
                                    <option value="">-- Pilih Tindakan Terapi --</option>
                                    @foreach(\App\Models\KodeTindakanTerapi::orderBy('kode')->get() as $tindakan)
                                        <option value="{{ $tindakan->idkode_tindakan_terapi }}" 
                                                {{ $detail->idkode_tindakan_terapi == $tindakan->idkode_tindakan_terapi ? 'selected' : '' }}>
                                            {{ $tindakan->kode }} - {{ $tindakan->deskripsi_tindakan_terapi }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="detail_edit{{ $detail->iddetail_rekam_medis }}" class="form-label">
                                    Detail Rincian <span class="text-danger">*</span>
                                </label>
                                <textarea class="form-control" 
                                          id="detail_edit{{ $detail->iddetail_rekam_medis }}" 
                                          name="detail" rows="4" 
                                      maxlength="1000" 
                                      required>{{ $detail->detail }}</textarea>
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-1"></i>Update Tindakan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
        @endif
    @endforeach
</div>
@endsection
