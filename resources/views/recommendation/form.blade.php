@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4>Cari Coffee Shop di Bandung</h4>
                </div>
                <div class="card-body">
                    <form action="{{ route('recommendation.process') }}" method="POST" id="recommendationForm">
                        @csrf
                        
                        {{-- Lokasi dengan Google Maps --}}
                        <div class="form-group mb-3">
                            <label>Lokasi Saya Saat Ini</label>
                            
                            {{-- Tombol deteksi otomatis --}}
                            <div class="mb-2">
                                <button type="button" class="btn btn-secondary" onclick="getCurrentLocation()">
                                    <i class="fas fa-location-dot"></i> Gunakan Lokasi Saya Saat Ini
                                </button>
                                <button type="button" class="btn btn-outline-secondary" onclick="searchAddress()">
                                    <i class="fas fa-search"></i> Cari Alamat
                                </button>
                            </div>
                            
                            {{-- Input alamat --}}
                            <input type="text" id="address" class="form-control mb-2" placeholder="Atau ketik alamat...">
                            
                            {{-- Leaflet Maps Container --}}
                            <div id="map" style="height: 400px; width: 100%; border-radius: 10px; margin-bottom: 10px;"></div>
                            
                            {{-- Hidden inputs untuk koordinat --}}
                            <input type="hidden" name="location" id="location" required>
                            <input type="hidden" name="latitude" id="latitude" required>
                            <input type="hidden" name="longitude" id="longitude" required>
                            
                            <small class="text-muted">
                                <i class="fas fa-info-circle"></i> Klik pada peta untuk memilih lokasi, atau klik tombol "Gunakan Lokasi Saya"
                            </small>
                        </div>
                        
                        {{-- Jarak --}}
                        <div class="form-group mb-3">
                            <label>Radius Maksimal (km)</label>
                            <select name="max_distance" id="max_distance" class="form-control" required>
                                <option value="1">1 km</option>
                                <option value="2">2 km</option>
                                <option value="3">3 km</option>
                                <option value="5" selected>5 km</option>
                                <option value="10">10 km</option>
                            </select>
                        </div>
                        
                        {{-- Budget --}}
                        <div class="form-group mb-3">
                            <label>Budget Range</label>
                            <select name="budget" class="form-control" required>
                                <option value="murah">Murah (< Rp25.000)</option>
                                <option value="sedang" selected>Sedang (Rp25.000 - Rp50.000)</option>
                                <option value="mahal">Mahal (> Rp50.000)</option>
                            </select>
                        </div>
                        
                        <h5>Fasilitas & Suasana</h5>
                        <hr>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="need_wifi" value="1" class="form-check-input" id="wifi">
                                    <label class="form-check-label" for="wifi">Butuh WiFi Cepat</label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="need_power_outlet" value="1" class="form-check-input" id="outlet">
                                    <label class="form-check-label" for="outlet">Butuh Colokan Listrik</label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="for_work" value="1" class="form-check-input" id="work">
                                    <label class="form-check-label" for="work">Cocok untuk Bekerja</label>
                                </div>
                            </div>
                            
                            <div class="col-md-6">
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="quiet" value="1" class="form-check-input" id="quiet">
                                    <label class="form-check-label" for="quiet">Suasana Sepi & Tenang</label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="outdoor" value="1" class="form-check-input" id="outdoor">
                                    <label class="form-check-label" for="outdoor">Area Outdoor</label>
                                </div>
                                
                                <div class="form-check mb-2">
                                    <input type="checkbox" name="need_smoking_area" value="1" class="form-check-input" id="smoking">
                                    <label class="form-check-label" for="smoking">Smoking Area</label>
                                </div>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary mt-3">
                            <i class="fas fa-search"></i> Cari Rekomendasi
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Leaflet CSS & JS --}}
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

<script>
    let map;
    let marker;
    let circle;
    
    // Inisialisasi Map
    function initMap() {
        // Koordinat pusat Bandung
        const bandung = [-6.9175, 107.6191];
        
        // Buat map
        map = L.map('map').setView(bandung, 13);
        
        // Tambahkan tile layer (peta)
        L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> &copy; <a href="https://carto.com/attributions">CARTO</a>',
            subdomains: 'abcd',
            maxZoom: 19,
            minZoom: 3
        }).addTo(map);
        
        // Buat marker (draggable)
        marker = L.marker(bandung, {
            draggable: true
        }).addTo(map);
        
        // Event ketika marker di-drag
        marker.on('dragend', function(e) {
            const pos = e.target.getLatLng();
            updateLocationFromLatLng(pos.lat, pos.lng);
            getAddressFromLatLng(pos.lat, pos.lng);
            showRadiusCircle(pos.lat, pos.lng);
        });
        
        // Event ketika map diklik
        map.on('click', function(e) {
            marker.setLatLng(e.latlng);
            updateLocationFromLatLng(e.latlng.lat, e.latlng.lng);
            getAddressFromLatLng(e.latlng.lat, e.latlng.lng);
            showRadiusCircle(e.latlng.lat, e.latlng.lng);
        });
    }
    
    // Update lokasi dari latitude/longitude
    function updateLocationFromLatLng(lat, lng) {
        document.getElementById('latitude').value = lat;
        document.getElementById('longitude').value = lng;
        document.getElementById('location').value = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;
        
        // Tampilkan pesan sukses
        const locationInput = document.getElementById('location');
        locationInput.style.borderColor = "green";
        setTimeout(() => {
            locationInput.style.borderColor = "";
        }, 2000);
    }
    
    // Tampilkan lingkaran radius
    function showRadiusCircle(lat, lng) {
        const radius = parseFloat(document.getElementById('max_distance').value) * 1000;
        
        // Hapus circle lama jika ada
        if (circle) {
            map.removeLayer(circle);
        }
        
        // Buat circle baru
        circle = L.circle([lat, lng], {
            radius: radius,
            color: '#FF5722',
            fillColor: '#FF5722',
            fillOpacity: 0.1,
            weight: 2
        }).addTo(map);
    }
    
    // Dapatkan lokasi saat ini dari browser
    function getCurrentLocation() {
        if (navigator.geolocation) {
            // Tampilkan loading
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mendapatkan lokasi...';
            btn.disabled = true;
            
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    const lat = position.coords.latitude;
                    const lng = position.coords.longitude;
                    const pos = [lat, lng];
                    
                    // Pindah map ke lokasi user
                    map.setView(pos, 15);
                    marker.setLatLng(pos);
                    
                    updateLocationFromLatLng(lat, lng);
                    showRadiusCircle(lat, lng);
                    getAddressFromLatLng(lat, lng);
                    
                    // Reset button
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                },
                function(error) {
                    showLocationError(error);
                    btn.innerHTML = originalText;
                    btn.disabled = false;
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        } else {
            alert("Browser tidak mendukung geolocation");
        }
    }
    
    // Dapatkan alamat dari koordinat (reverse geocoding dengan Nominatim)
    async function getAddressFromLatLng(lat, lng) {
        try {
            const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&zoom=18&addressdetails=1`);
            const data = await response.json();
            
            if (data && data.display_name) {
                document.getElementById('address').value = data.display_name;
            }
        } catch (error) {
            console.error('Error getting address:', error);
        }
    }
    
    // Cari alamat (forward geocoding)
    async function searchAddress() {
        const address = document.getElementById('address').value;
        
        if (!address.trim()) {
            alert("Silakan masukkan alamat terlebih dahulu");
            return;
        }
        
        // Tampilkan loading
        const btn = event.target;
        const originalText = btn.innerHTML;
        btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Mencari...';
        btn.disabled = true;
        
        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(address)}&limit=1`
            );
            const data = await response.json();
            
            if (data.length > 0) {
                const lat = parseFloat(data[0].lat);
                const lon = parseFloat(data[0].lon);
                
                map.setView([lat, lon], 16);
                marker.setLatLng([lat, lon]);
                
                updateLocationFromLatLng(lat, lon);
                showRadiusCircle(lat, lon);
            } else {
                alert("Alamat tidak ditemukan. Silakan coba dengan alamat yang lebih spesifik.");
            }
        } catch (error) {
            console.error('Error searching address:', error);
            alert("Terjadi kesalahan saat mencari alamat");
        } finally {
            btn.innerHTML = originalText;
            btn.disabled = false;
        }
    }
    
    // Update radius circle ketika radius berubah
    document.getElementById('max_distance').addEventListener('change', function() {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        if (lat && lng) {
            showRadiusCircle(parseFloat(lat), parseFloat(lng));
        }
    });
    
    // Tampilkan error lokasi
    function showLocationError(error) {
        let message = "";
        switch(error.code) {
            case error.PERMISSION_DENIED:
                message = "⚠️ Izin lokasi ditolak. Silakan izinkan akses lokasi di browser Anda.";
                break;
            case error.POSITION_UNAVAILABLE:
                message = "⚠️ Informasi lokasi tidak tersedia.";
                break;
            case error.TIMEOUT:
                message = "⚠️ Waktu permintaan lokasi habis. Silakan coba lagi.";
                break;
            default:
                message = "⚠️ Terjadi kesalahan saat mengambil lokasi.";
                break;
        }
        alert(message);
    }
    
    // Validasi form sebelum submit
    document.getElementById('recommendationForm').addEventListener('submit', function(e) {
        const lat = document.getElementById('latitude').value;
        const lng = document.getElementById('longitude').value;
        
        if (!lat || !lng) {
            e.preventDefault();
            alert("⚠️ Silakan pilih lokasi terlebih dahulu!\n\nKlik pada peta atau gunakan tombol 'Gunakan Lokasi Saya Saat Ini'");
            return false;
        }
    });
    
    // Jalankan initMap saat halaman selesai loading
    document.addEventListener('DOMContentLoaded', function() {
        initMap();
    });
</script>
@endsection