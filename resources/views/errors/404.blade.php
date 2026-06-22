@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="card">
                <div class="card-body">
                    <h1 class="display-1">404</h1>
                    <h3>Halaman Tidak Ditemukan</h3>
                    <p>Maaf, halaman yang Anda cari tidak tersedia.</p>
                    <a href="{{ url('/') }}" class="btn btn-primary">Kembali ke Beranda</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection