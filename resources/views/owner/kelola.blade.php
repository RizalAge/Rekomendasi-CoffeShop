@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-info text-white">Kelola Data & Fasilitas Cafe: <strong>{{ $cafe->name }}</strong></div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    <form action="{{ route('owner.kelola.update') }}" method="POST">
                        @csrf
                        {{-- Catatan: Rute POST update data belum dibuat, form ini adalah template UI-nya --}}
                        
                        <h5 class="mb-3">Informasi Umum</h5>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="address" rows="2">{{ $cafe->address }}</textarea>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label">Harga Termurah (Min Price)</label>
                                <input type="number" class="form-control" name="min_price" value="{{ $cafe->min_price }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Harga Termahal (Max Price)</label>
                                <input type="number" class="form-control" name="max_price" value="{{ $cafe->max_price }}">
                            </div>
                        </div>

                        <h5 class="mb-3">Fasilitas</h5>
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="wifi" value="1" {{ $cafe->wifi ? 'checked' : '' }}>
                                    <label class="form-check-label">WiFi Cepat</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="power_outlet" value="1" {{ $cafe->power_outlet ? 'checked' : '' }}>
                                    <label class="form-check-label">Stop Kontak</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="smoking_area" value="1" {{ $cafe->smoking_area ? 'checked' : '' }}>
                                    <label class="form-check-label">Smoking Area</label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="outdoor_seating" value="1" {{ $cafe->outdoor_seating ? 'checked' : '' }}>
                                    <label class="form-check-label">Area Outdoor</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="quiet_atmosphere" value="1" {{ $cafe->quiet_atmosphere ? 'checked' : '' }}>
                                    <label class="form-check-label">Suasana Tenang</label>
                                </div>
                                <div class="form-check mb-2">
                                    <input class="form-check-input" type="checkbox" name="suitable_for_work" value="1" {{ $cafe->suitable_for_work ? 'checked' : '' }}>
                                    <label class="form-check-label">Cocok untuk Bekerja (WFC)</label>
                                </div>
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary">Kembali</a>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection