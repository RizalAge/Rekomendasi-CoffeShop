@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h4 class="mb-3">Daftar Coffee Shop di Bandung</h4>
                    <form action="{{ route('coffee-shops.search') }}" method="GET" class="mb-4">
                        <div class="row">
                            <div class="col-md-4">
                                <input type="text" name="search" class="form-control" placeholder="Cari nama atau alamat..." value="{{ request('search') }}">
                            </div>
                            <div class="col-md-3">
                                <select name="price_range" class="form-control">
                                    <option value="">Semua Harga</option>
                                    <option value="murah" {{ request('price_range') == 'murah' ? 'selected' : '' }}>Murah (< Rp25.000)</option>
                                    <option value="sedang" {{ request('price_range') == 'sedang' ? 'selected' : '' }}>Sedang (Rp25.000 - Rp50.000)</option>
                                    <option value="mahal" {{ request('price_range') == 'mahal' ? 'selected' : '' }}>Mahal (> Rp50.000)</option>
                                </select>
                            </div>
                            <div class="col-md-5">
                                <button type="submit" class="btn btn-primary">Cari</button>
                                <a href="{{ route('coffee-shops.index') }}" class="btn btn-secondary">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row">
        @forelse($coffeeShops as $shop)
        <div class="col-md-4 col-lg-3 mb-4">
            {{-- Tambahkan d-flex flex-column agar card body fleksibel --}}
            <div class="card h-100 shop-card d-flex flex-column">
                @php
            
                 $images = $shop->image ? (is_array(json_decode($shop->image, true)) ? json_decode($shop->image, true) : [$shop->image]) : [];
                  @endphp
                @if(count($images) > 0)
                <img src="{{ asset('storage/' . $images[0]) }}" class="card-img-top" style="height: 180px; object-fit: cover;" alt="{{ $shop->name }}">
                @else
                <div class="card-img-top bg-secondary d-flex align-items-center justify-content-center" style="height: 180px;">
                    <i class="fas fa-coffee fa-3x text-white"></i>
                 </div>
                @endif
                
                {{-- d-flex flex-column memaksa isi card memanjang --}}
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title">{{ $shop->name }}</h5>
                    <p class="card-text text-muted small">
                        <i class="fas fa-map-marker-alt"></i> {{ Str::limit($shop->address, 50) }}
                    </p>
                    
                    <div class="mb-2">
                        <span class="badge bg-info">{{ ucfirst($shop->price_range) }}</span>
                        <span class="badge bg-warning text-dark">⭐ {{ $shop->rating_avg ?? 0 }}/5</span>
                        @if(isset($shop->distance))
                        <span class="badge bg-secondary">📍 {{ $shop->distance }} km</span>
                        @endif
                    </div>
                    
                    {{-- mt-auto akan mendorong elemen ini dan tombol di bawahnya ke posisi terbawah --}}
                    <div class="mt-auto mb-3">
                        @if($shop->wifi) <i class="fas fa-wifi text-success" title="WiFi"></i> @endif
                        @if($shop->power_outlet) <i class="fas fa-plug text-success" title="Colokan Listrik"></i> @endif
                        @if($shop->outdoor_seating) <i class="fas fa-tree text-success" title="Outdoor Seating"></i> @endif
                        @if($shop->suitable_for_work) <i class="fas fa-laptop text-success" title="Cocok untuk Kerja"></i> @endif
                    </div>
                    
                    {{-- Tombol sekarang akan selalu menempel di bawah karena mt-auto di atasnya --}}
                    <a href="{{ route('coffee-shops.show', $shop->id) }}" class="btn btn-sm btn-primary w-100">
                        Lihat Detail
                    </a>
                </div>
            </div>
        </div>
        @empty
        <div class="col-md-12">
            <div class="alert alert-warning text-center">
                <i class="fas fa-exclamation-triangle"></i> Belum ada coffee shop yang terdaftar.
            </div>
        </div>
        @endforelse
    </div>
    
    <div class="row mt-4">
        <div class="col-md-12">
            {{ $coffeeShops->links() }}
        </div>
    </div>
</div>

<style>
.shop-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}
.shop-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}
</style>
@endsection