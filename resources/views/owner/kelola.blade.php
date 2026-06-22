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
                        
                        <h5 class="mb-3">Informasi Umum</h5>
                        <div class="mb-3">
                            <label class="form-label">Alamat Lengkap</label>
                            <textarea class="form-control" name="address" rows="2">{{ $cafe->address }}</textarea>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Harga Termurah (Min Price)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control format-rupiah" id="min_price" name="min_price" 
                                           value="{{ (!empty($cafe->min_price) && $cafe->min_price > 0) ? number_format($cafe->min_price, 0, '', '.') : '' }}" 
                                           placeholder="0" required>
                                </div>
                            </div>
                            
                            <div class="col-md-6 mt-3 mt-md-0">
                                <label class="form-label">Harga Termahal (Max Price)</label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="text" class="form-control format-rupiah" id="max_price" name="max_price" 
                                           value="{{ (!empty($cafe->max_price) && $cafe->max_price > 0) ? number_format($cafe->max_price, 0, '', '.') : '' }}" 
                                           placeholder="0" required>
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
    const rupiahInputs = document.querySelectorAll('.format-rupiah');
    const form = document.querySelector('form');

    function formatRupiah(angka) {
        let number_string = angka.replace(/[^,\d]/g, '').toString(),
            split = number_string.split(','),
            sisa  = split[0].length % 3,
            rupiah  = split[0].substr(0, sisa),
            ribuan  = split[0].substr(sisa).match(/\d{3}/gi);

        if (ribuan) {
            let separator = sisa ? '.' : '';
            rupiah += separator + ribuan.join('.');
        }
        return rupiah;
    }

    rupiahInputs.forEach(function(input) {
        input.addEventListener('keyup', function(e) {
            this.value = formatRupiah(this.value);
        });
    });

    if(form) {
        form.addEventListener('submit', function() {
            rupiahInputs.forEach(function(input) {
                input.value = input.value.replace(/\./g, '');
            });
        });
    }
});
</script>
@endsection