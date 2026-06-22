@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-danger text-white">Dashboard Admin</div>

                <div class="card-body">
                    <h4>Selamat datang, Admin {{ Auth::user()->name }}!</h4>
                    <p>Di sini Anda bisa mengelola seluruh data sistem dan memvalidasi pengajuan cafe.</p>
                    
                    <hr>
                    <a href="{{ route('admin.validasi.index') }}" class="btn btn-primary">
                        Cek Pengajuan Cafe
                    </a>
                    <a href="{{ route('admin.cafe_disetujui') }}" class="btn btn-success px-4 py-2 fw-bold">
                            Daftar Cafe Disetujui
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection