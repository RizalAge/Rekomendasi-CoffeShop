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
                        <input type="hidden" name="cafe_id" value="{{ $cafe->id }}">

                        <h5 class="mb-3">Informasi Umum</h5>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="address" rows="2">{{ $cafe->address }}</textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">No. Telepon / WA</label>
                            <input type="text" class="form-control" name="phone" value="{{ $cafe->phone }}" placeholder="Contoh: 08123456789">
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Jam Buka</label>
                                <input type="time" class="form-control" name="open_time" value="{{ $cafe->open_time ?? '08:00' }}">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Jam Tutup</label>
                                <input type="time" class="form-control" name="close_time" value="{{ $cafe->close_time ?? '22:00' }}">
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Harga Termurah (Min Price)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control format-rupiah" id="min_price_display" 
                                           value="{{ (!empty($cafe->min_price) && $cafe->min_price > 0) ? number_format($cafe->min_price, 0, ',', '.') : '' }}" 
                                           placeholder="0">
                                    <input type="hidden" name="min_price" id="min_price" value="{{ $cafe->min_price ?? 0 }}">
                                </div>
                            </div>
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="form-label">Harga Termahal (Max Price)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control format-rupiah" id="max_price_display" 
                                           value="{{ (!empty($cafe->max_price) && $cafe->max_price > 0) ? number_format($cafe->max_price, 0, ',', '.') : '' }}" 
                                           placeholder="0">
                                    <input type="hidden" name="max_price" id="max_price" value="{{ $cafe->max_price ?? 0 }}">
                                </div>
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

                        <div class="row g-2 mt-4">
                            <div class="col-12 col-md-6">
                                <button type="submit" class="btn w-100 py-2" style="background-color: #6f42c1; color: white; border-color: #6f42c1;">
                                    Simpan Perubahan
                                </button>
                            </div>
                            <div class="col-12 col-md-6">
                                <a href="{{ route('owner.dashboard') }}" class="btn btn-secondary w-100 py-2 text-center">
                                    Kembali
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function() {
    const fields = [
        { display: document.getElementById('min_price_display'), hidden: document.getElementById('min_price') },
        { display: document.getElementById('max_price_display'), hidden: document.getElementById('max_price') },
    ];

    function toNumber(val) {
        return parseInt(val.replace(/\./g, '').replace(/,/g, '')) || 0;
    }

    function formatRupiah(val) {
        return toNumber(val).toLocaleString('id-ID');
    }

    fields.forEach(function(field) {
        field.display.addEventListener('keyup', function() {
            const raw = toNumber(this.value);
            field.hidden.value = raw;
            this.value = raw > 0 ? raw.toLocaleString('id-ID') : '';
        });
    });
});
</script>
@endsection