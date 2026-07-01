<?php

namespace App\Http\Controllers;

use App\Models\CoffeeShop;
use Illuminate\Http\Request;

class CoffeeShopController extends Controller
{
    /**
     * Display a listing of the coffee shops.
     */
    public function index()
    {
        // Hanya menampilkan cafe yang disetujui (Approved)
        $coffeeShops = CoffeeShop::where('status', 'approved')->paginate(12); 
        return view('coffee-shops.index', compact('coffeeShops'));
    }
    
    /**
     * Display the specified coffee shop.
     */
    public function show($id)
    {
        // Pastikan hanya cafe yang di-approve yang bisa dilihat detailnya
        $coffeeShop = CoffeeShop::where('status', 'approved')->findOrFail($id);
        
        // Kirim ke view detail
        return view('coffee-shops.show', compact('coffeeShop'));
    }
    
    /**
     * Search coffee shops based on criteria (optional)
     */
    public function search(Request $request)
    {
        // MULAI DENGAN WAJIB STATUS APPROVED
        $query = CoffeeShop::where('status', 'approved');
        
        // Filter berdasarkan nama atau alamat
        // PENTING: Gunakan closure (function($q)) agar "orWhere" tidak merusak filter "status=approved"
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('address', 'like', '%' . $request->search . '%');
            });
        }
        
        // Filter berdasarkan price range
        if ($request->has('price_range') && $request->price_range != '') {
            $query->where('price_range', $request->price_range);
        }
        
        // Filter berdasarkan fasilitas
        if ($request->has('wifi') && $request->wifi) {
            $query->where('wifi', true);
        }
        
        if ($request->has('power_outlet') && $request->power_outlet) {
            $query->where('power_outlet', true);
        }
        
        if ($request->has('outdoor_seating') && $request->outdoor_seating) {
            $query->where('outdoor_seating', true);
        }
        
        if ($request->has('suitable_for_work') && $request->suitable_for_work) {
            $query->where('suitable_for_work', true);
        }
        
        // Urutkan berdasarkan rating tertinggi
        $coffeeShops = $query->orderBy('rating_avg', 'desc')->paginate(12);
        
        return view('coffee-shops.index', compact('coffeeShops'));
    }
    
    /**
     * Get coffee shops by location (API endpoint)
     */
    public function nearby(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'nullable|numeric|min:1|max:20'
        ]);
        
        $latitude = $request->latitude;
        $longitude = $request->longitude;
        $radius = $request->radius ?? 5; 
        
        // Hanya ambil cafe yang statusnya approved
        $coffeeShops = CoffeeShop::where('status', 'approved')->get();
        
        $nearbyShops = [];
        foreach ($coffeeShops as $shop) {
            $distance = $this->calculateDistance(
                $latitude, $longitude, 
                $shop->latitude, $shop->longitude
            );
            
            if ($distance <= $radius) {
                $shop->distance = round($distance, 2);
                $nearbyShops[] = $shop;
            }
        }
        
        // Urutkan berdasarkan jarak terdekat
        usort($nearbyShops, function($a, $b) {
            return $a->distance <=> $b->distance;
        });
        
        return response()->json([
            'success' => true,
            'data' => $nearbyShops
        ]);
    }
    
    /**
     * Calculate distance between two coordinates (Haversine formula)
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371; 
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + 
             cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        return $earthRadius * $c;
    }
}