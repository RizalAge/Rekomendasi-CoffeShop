@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-10">
            <div class="card">
                <div class="card-header bg-success text-white">
                    <h4>Rekomendasi Coffee Shop</h4>
                </div>
                <div class="card-body">
                    @if(session('error'))
                        <div class="alert alert-warning">
                            {{ session('error') }}
                        </div>
                    @endif

                    @if(isset($coffeeShops) && $coffeeShops->count() > 0)
                        <div class="alert alert-success">
                            <strong>Ditemukan {{ $coffeeShops->count() }} coffee shop!</strong>
                        </div>
                        
                        @foreach($coffeeShops as $shop)
                            <div class="card mb-3">
                                <div class="card-body">
                                    <h5>{{ $shop->name }}</h5>
                                    <p class="text-muted">{{ $shop->description ?? 'Tidak ada deskripsi' }}</p>
                                    <hr>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <p><strong>📍 Alamat:</strong><br>{{ $shop->address }}</p>
                                            <p><strong>💰 Budget:</strong> Rp {{ number_format($shop->min_price) }} - {{ number_format($shop->max_price) }}</p>
                                            <p><strong>📏 Jarak:</strong> {{ round($shop->distance, 2) }} km</p>
                                        </div>
                                        <div class="col-md-6">
                                            <p><strong>⭐ Rating:</strong> {{ $shop->rating_avg ?? 'Belum ada rating' }}</p>
                                            <p><strong>🕒 Jam Operasional:</strong> {{ $shop->open_time ?? 'N/A' }} - {{ $shop->close_time ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="mt-2">
                                        <strong>Fasilitas:</strong><br>
                                        @if($shop->wifi) <span class="badge bg-primary">WiFi</span> @endif
                                        @if($shop->power_outlet) <span class="badge bg-info">Colokan Listrik</span> @endif
                                        @if($shop->suitable_for_work) <span class="badge bg-success">Work Friendly</span> @endif
                                        @if($shop->quiet_atmosphere) <span class="badge bg-secondary">Suasana Tenang</span> @endif
                                        @if($shop->outdoor_seating) <span class="badge bg-warning">Area Outdoor</span> @endif
                                        @if($shop->smoking_area) <span class="badge bg-danger">Smoking Area</span> @endif
                                        @if(isset($shop->manual_brew) && $shop->manual_brew) <span class="badge bg-dark">Manual Brew</span> @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                        
                        <a href="{{ route('recommendation.form') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cari Lagi
                        </a>
                    @else
                        <div class="alert alert-info">
                            <h5>Tidak ada coffee shop yang ditemukan</h5>
                            <p>Coba:</p>
                            <ul>
                                <li>✅ Perluas radius pencarian (coba 10 km)</li>
                                <li>✅ Kurangi filter fasilitas</li>
                                <li>✅ Ubah budget range</li>
                                <li>✅ Pilih lokasi yang berbeda di peta</li>
                            </ul>
                            <a href="{{ route('recommendation.form') }}" class="btn btn-primary">
                                <i class="fas fa-arrow-left"></i> Kembali ke Pencarian
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection