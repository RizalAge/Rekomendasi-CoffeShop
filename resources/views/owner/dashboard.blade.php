@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">Dashboard Pemilik Cafe</div>

                <div class="card-body">
                    <h4>Selamat datang, {{ Auth::user()->name }}!</h4>
                    <hr>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <div class="mb-3">
                        <a href="{{ route('owner.upload.form') }}" class="btn btn-warning">
                            + Ajukan Cafe Baru
                        </a>
                    </div>

                    <h5>Daftar Cafe Anda</h5>
                    <div class="table-responsive">
                        <table class="table table-bordered">
                            <thead class="table-light">
                                <tr>
                                    <th>Nama Cafe</th>
                                    <th>Status Validasi</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($myCafes ?? [] as $cafe)
                                <tr>
                                    <td>{{ $cafe->name }}</td>
                                    <td>
                                        @if($cafe->status == 'pending')
                                            <span class="badge bg-warning text-dark">Menunggu Validasi</span>
                                        @elseif($cafe->status == 'approved')
                                            <span class="badge bg-success">Disetujui</span>
                                        @else
                                            <span class="badge bg-danger">Ditolak</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($cafe->status == 'approved')
                                            <a href="{{ route('owner.kelola') }}" class="btn btn-sm btn-info">Kelola Menu & Fasilitas</a>
                                        @else
                                            <button class="btn btn-sm btn-secondary" disabled>Terkunci</button>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="3" class="text-center">Anda belum mendaftarkan cafe. Silakan klik tombol "Ajukan Cafe Baru".</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection