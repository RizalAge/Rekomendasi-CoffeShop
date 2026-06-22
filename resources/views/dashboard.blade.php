@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    <h4>Selamat datang, {{ Auth::user()->name }}!</h4>
                    <p>Anda sudah login ke sistem rekomendasi coffee shop Bandung.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('recommendation.form') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari Rekomendasi Coffee Shop
                        </a>
                        <a href="{{ route('coffee-shops.index') }}" class="btn btn-secondary">
                            <i class="fas fa-list"></i> Lihat Semua Coffee Shop
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection