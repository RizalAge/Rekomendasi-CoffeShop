@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8">
            <div class="card">
                @if($coffeeShop->image)
                <img src="{{ asset('storage/'.$coffeeShop->image) }}" class="card-img-top" style="max-height: 400px; object-fit: cover;" alt="{{ $coffeeShop->name }}">
                @endif
                
                <div class="card-body">
                    <h2 class="card-title">{{ $coffeeShop->name }}</h2>
                    
                    <div class="mb-3">
                        <span class="badge bg-info">{{ ucfirst($coffeeShop->price_range) }}</span>
                        <span class="badge bg-warning text-dark">⭐ {{ $coffeeShop->rating_avg }} ({{ $coffeeShop->total_reviews }} review)</span>
                    </div>
                    
                    <p class="card-text">{{ $coffeeShop->description }}</p>
                    
                    <hr>
                    
                    <h5><i class="fas fa-map-marker-alt"></i> Alamat</h5>
                    <p>{{ $coffeeShop->address }}</p>
                    
                    <h5><i class="fas fa-clock"></i> Jam Operasional</h5>
                    <p>{{ date('H:i', strtotime($coffeeShop->open_time)) }} - {{ date('H:i', strtotime($coffeeShop->close_time)) }}</p>
                    
                    <h5><i class="fas fa-phone"></i> Kontak</h5>
                    <p>{{ $coffeeShop->phone ?? 'Tidak tersedia' }}</p>
                    
                    <h5><i class="fas fa-tag"></i> Harga</h5>
                    <p>Rp {{ number_format($coffeeShop->min_price, 0, ',', '.') }} - Rp {{ number_format($coffeeShop->max_price, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>
        
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Fasilitas & Suasana</h5>
                </div>
                <div class="card-body">
                    <ul class="list-unstyled">
                        <li class="mb-2">
                            @if($coffeeShop->wifi)
                            <i class="fas fa-check-circle text-success"></i> WiFi Gratis
                            @else
                            <i class="fas fa-times-circle text-danger"></i> Tidak ada WiFi
                            @endif
                        </li>
                        <li class="mb-2">
                            @if($coffeeShop->power_outlet)
                            <i class="fas fa-check-circle text-success"></i> Colokan Listrik
                            @else
                            <i class="fas fa-times-circle text-danger"></i> Tidak ada colokan
                            @endif
                        </li>
                        <li class="mb-2">
                            @if($coffeeShop->smoking_area)
                            <i class="fas fa-check-circle text-success"></i> Smoking Area
                            @else
                            <i class="fas fa-times-circle text-danger"></i> Non-Smoking
                            @endif
                        </li>
                        <li class="mb-2">
                            @if($coffeeShop->outdoor_seating)
                            <i class="fas fa-check-circle text-success"></i> Area Outdoor
                            @else
                            <i class="fas fa-times-circle text-danger"></i> Indoor Only
                            @endif
                        </li>
                        <li class="mb-2">
                            @if($coffeeShop->suitable_for_work)
                            <i class="fas fa-check-circle text-success"></i> Cocok untuk Bekerja
                            @else
                            <i class="fas fa-times-circle text-danger"></i> Kurang cocok untuk kerja
                            @endif
                        </li>
                        <li class="mb-2">
                            @if($coffeeShop->quiet_atmosphere)
                            <i class="fas fa-check-circle text-success"></i> Suasana Tenang
                            @else
                            <i class="fas fa-times-circle text-danger"></i> Suasana Ramai
                            @endif
                        </li>
                        <li class="mb-2">
                            @if($coffeeShop->manual_brew)
                            <i class="fas fa-check-circle text-success"></i> Manual Brew
                            @else
                            <i class="fas fa-times-circle text-danger"></i> Tidak ada manual brew
                            @endif
                        </li>
                    </ul>
                </div>
            </div>
            
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h5 class="mb-0">Lokasi</h5>
                </div>
                <div class="card-body">
                    <button onclick="openGoogleMaps()" class="btn btn-primary w-100 mb-2">
                        <i class="fab fa-google"></i> Buka di Google Maps
                    </button>
                    <button onclick="getDirection()" class="btn btn-secondary w-100">
                        <i class="fas fa-directions"></i> Dapatkan Petunjuk Arah
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            <a href="{{ route('coffee-shops.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
            <a href="{{ route('recommendation.form') }}" class="btn btn-primary">
                <i class="fas fa-search"></i> Cari Rekomendasi Lain
            </a>
        </div>
    </div>
</div>

<script>
function openGoogleMaps() {
    window.open(`https://www.google.com/maps/search/?api=1&query={{ $coffeeShop->latitude }},{{ $coffeeShop->longitude }}`, '_blank');
}

function getDirection() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(function(position) {
            const url = `https://www.google.com/maps/dir/${position.coords.latitude},${position.coords.longitude}/{{ $coffeeShop->latitude }},{{ $coffeeShop->longitude }}`;
            window.open(url, '_blank');
        }, function() {
            openGoogleMaps();
        });
    } else {
        openGoogleMaps();
    }
}
</script>
@endsection