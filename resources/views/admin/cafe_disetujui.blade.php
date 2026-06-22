@extends('layouts.app') 

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white fw-bold">Daftar Cafe Disetujui (Approved)</div>

                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle">
                            <thead class="table-light text-center">
                                <tr>
                                    <th>No</th>
                                    <th>Nama Pemilik</th>
                                    <th>Nama Cafe</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($approvedCafes ?? [] as $index => $cafe)
                                <tr>
                                    <td class="text-center">{{ $index + 1 }}</td>
                                    <td>{{ $cafe->nama_pemilik ?? ($cafe->owner->name ?? 'Tidak diketahui') }}</td>
                                    <td class="fw-bold text-success">{{ $cafe->name }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-success">Disetujui</span>
                                    </td>
                                    <td class="text-center">
                                        {{-- Tombol Lihat Detail memanggil Modal --}}
                                        <button type="button" class="btn btn-sm btn-info text-white fw-bold px-3" data-bs-toggle="modal" data-bs-target="#detailModal{{ $cafe->id }}">
                                            Lihat Detail
                                        </button>
                                        
                                        {{-- Tombol Batalkan Persetujuan (Opsional, untuk keamanan jika admin salah pencet) --}}
                                        <form action="{{ route('admin.validasi.process', ['id' => $cafe->id, 'status' => 'rejected']) }}" method="POST" class="d-inline ms-1">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-outline-danger" onclick="return confirm('Yakin ingin membatalkan status cafe ini dan menolaknya?')">Batalkan</button>
                                        </form>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center py-4 text-muted">Belum ada cafe yang disetujui.</td>
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

{{-- KUMPULAN MODAL DETAIL CAFE --}}
@foreach($approvedCafes ?? [] as $cafe)
<div class="modal fade" id="detailModal{{ $cafe->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title fw-bold">Detail Cafe: {{ $cafe->name }}</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
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
                    @php
                        // Logika pintar untuk membaca data lama (1 foto) & data baru (4 foto)
                        $dokumens = $cafe->dokumen_persyaratan;
                        if (is_string($dokumens)) {
                            $decoded = json_decode($dokumens, true);
                            $dokumens = (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) ? $decoded : [$dokumens];
                        }
                    @endphp

                    @if(!empty($dokumens))
                        @foreach($dokumens as $dokumen)
                            <div class="col-6 col-md-3">
                                @if(\Illuminate\Support\Str::endsWith(strtolower($dokumen), ['.pdf']))
                                    <a href="{{ asset('storage/' . $dokumen) }}" target="_blank" class="d-flex flex-column align-items-center justify-content-center border rounded bg-light text-decoration-none text-dark h-100 p-3 shadow-sm" style="min-height: 120px;">
                                        <span style="font-size: 2.5rem;">📄</span>
                                        <span class="small mt-2 text-center fw-bold">Buka PDF</span>
                                    </a>
                                @else
                                    <a href="{{ asset('storage/' . $dokumen) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $dokumen) }}" class="img-fluid rounded border shadow-sm w-100" style="height: 120px; object-fit: cover;" alt="Foto Cafe">
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