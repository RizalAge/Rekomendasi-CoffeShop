@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-danger text-white">Daftar Pengajuan Cafe (Pending)</div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pemilik</th>
                                    <th>Nama Cafe</th>
                                    <th>Dokumen Persyaratan</th>
                                    <th>Status</th>
                                    <th>Aksi Validasi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pendingCafes ?? [] as $index => $cafe)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $cafe->owner->name ?? 'Tidak diketahui' }}</td>
                                    <td class="fw-bold">{{ $cafe->name }}</td>
                                    <td class="text-center">
                                        {{-- Tombol Lihat Detail yang menggantikan File 1, 2, 3 --}}
                                        <button type="button" class="btn btn-sm btn-info text-white fw-bold px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $cafe->id }}">
                                            Lihat Detail
                                        </button>
                                    </td>
                                    <td class="text-center">
                                        <span class="badge bg-warning text-dark">{{ ucfirst($cafe->status) }}</span>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('admin.validasi.process', ['id' => $cafe->id, 'status' => 'approved']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Yakin ingin menyetujui cafe ini?')">Setuju</button>
                                        </form>

                                        <form action="{{ route('admin.validasi.process', ['id' => $cafe->id, 'status' => 'rejected']) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menolak cafe ini?')">Tolak</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">Saat ini tidak ada pengajuan cafe baru.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary mt-3">Kembali ke Dashboard</a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- 
======================================================================
KUMPULAN MODAL DETAIL CAFE
Ditaruh DI LUAR tabel agar tidak merusak struktur layout HTML tabel Anda
======================================================================
--}}
@foreach($pendingCafes ?? [] as $cafe)
<div class="modal fade" id="detailModal{{ $cafe->id }}" tabindex="-1" aria-labelledby="modalLabel{{ $cafe->id }}" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-info text-white">
                <h5 class="modal-title fw-bold" id="modalLabel{{ $cafe->id }}">Detail & Dokumen: {{ $cafe->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <div class="row mb-3">
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td width="100" class="fw-bold">Pemilik</td><td>: {{ $cafe->nama_pemilik }} <br> (NIK: {{ $cafe->nik_pemilik }})</td></tr>
                            <tr><td class="fw-bold">Alamat</td><td>: {{ $cafe->alamat_cafe ?? $cafe->address }}</td></tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <table class="table table-sm table-borderless">
                            <tr><td width="100" class="fw-bold">Kategori</td><td>: {{ is_array($cafe->kategori) ? implode(', ', $cafe->kategori) : '-' }}</td></tr>
                            <tr><td class="fw-bold">Fasilitas</td><td>: {{ is_array($cafe->fasilitas) ? implode(', ', $cafe->fasilitas) : '-' }}</td></tr>
                        </table>
                    </div>
                </div>

                <hr>
                <h6 class="fw-bold mb-3">Lampiran Foto & Dokumen:</h6>
                
                <div class="row g-3">
                    @if(is_array($cafe->dokumen_persyaratan) && count($cafe->dokumen_persyaratan) > 0)
                        @foreach($cafe->dokumen_persyaratan as $dokumen)
                            <div class="col-6 col-md-3">
                                @if(\Illuminate\Support\Str::endsWith(strtolower($dokumen), ['.pdf']))
                                    <a href="{{ asset('storage/' . $dokumen) }}" target="_blank" class="d-flex flex-column align-items-center justify-content-center border rounded bg-light text-decoration-none text-dark h-100 p-3 shadow-sm" style="min-height: 120px;">
                                        <span style="font-size: 2.5rem;">📄</span>
                                        <span class="small mt-2 text-center fw-bold">Buka PDF</span>
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $dokumen) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $dokumen) }}" class="img-fluid rounded border shadow-sm w-100" style="height: 120px; object-fit: cover;" alt="Lampiran">
                                    </a>
                                @endif
                            </div>
                        @endforeach
                    @else
                        <div class="col-12 text-danger">Tidak ada dokumen yang dilampirkan.</div>
                    @endif
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection