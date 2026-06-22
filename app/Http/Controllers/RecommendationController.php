<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoffeeShop;
use Illuminate\Support\Facades\Log;

class RecommendationController extends Controller
{
    /**
     * Menampilkan form rekomendasi coffee shop
     */
    public function index()
    {
        return view('recommendation.form');
    }
    
    /**
     * Memproses rekomendasi coffee shop
     */
    public function process(Request $request)
    {
        // Debug: lihat input dari form
        Log::info('Input Filter:', $request->all());

        // Validasi
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'max_distance' => 'required|numeric',
            'budget' => 'required|in:murah,sedang,mahal',
        ]);

        $userLat = $request->latitude;
        $userLng = $request->longitude;
        $maxDistance = $request->max_distance;

        // Query
        $query = CoffeeShop::query();

        // Filter Budget (gunakan min_price dan max_price)
        if ($request->budget == 'murah') {
            $query->where('max_price', '<', 25000);
        } elseif ($request->budget == 'sedang') {
            $query->where('min_price', '>=', 25000)->where('max_price', '<=', 50000);
        } elseif ($request->budget == 'mahal') {
            $query->where('min_price', '>', 50000);
        }

        // Filter Fasilitas (sesuaikan nama kolom)
        if ($request->has('need_wifi') && $request->need_wifi == 1) {
            $query->where('wifi', 1);
        }

        if ($request->has('need_power_outlet') && $request->need_power_outlet == 1) {
            $query->where('power_outlet', 1);
        }

        if ($request->has('for_work') && $request->for_work == 1) {
            $query->where('suitable_for_work', 1);
        }

        if ($request->has('quiet') && $request->quiet == 1) {
            $query->where('quiet_atmosphere', 1);
        }

        if ($request->has('outdoor') && $request->outdoor == 1) {
            $query->where('outdoor_seating', 1);
        }

        if ($request->has('need_smoking_area') && $request->need_smoking_area == 1) {
            $query->where('smoking_area', 1);
        }

        // Hitung jarak (Haversine formula)
        $query->selectRaw("
            *,
            (
                6371 * acos(
                    cos(radians(?)) * cos(radians(latitude)) * cos(radians(longitude) - radians(?)) +
                    sin(radians(?)) * sin(radians(latitude))
                )
            ) AS distance
        ", [$userLat, $userLng, $userLat])
        ->having('distance', '<=', $maxDistance)
        ->orderBy('distance');

        $coffeeShops = $query->get();

        // Debug: lihat jumlah hasil
        Log::info('Jumlah coffee shop ditemukan: ' . $coffeeShops->count());

        if ($coffeeShops->isEmpty()) {
            return back()->with('error', 'Tidak ada coffee shop yang sesuai dengan filter Anda. Coba perluas radius atau kurangi filter.');
        }

        return view('recommendation.results', compact('coffeeShops'));
    }
}