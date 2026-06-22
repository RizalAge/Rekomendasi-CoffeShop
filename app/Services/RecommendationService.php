<?php

namespace App\Services;

use App\Models\CoffeeShop;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class RecommendationService
{
    protected $userWeight;
    
    public function __construct()
    {
        $this->userWeight = Auth::user()->criteriaWeight ?? $this->getDefaultWeight();
    }
    
    private function getDefaultWeight()
    {
        return (object)[
            'wifi_weight' => 50,
            'price_weight' => 70,
            'atmosphere_weight' => 60,
            'distance_weight' => 80,
            'facility_weight' => 50
        ];
    }
    
    public function getRecommendations($userCriteria, $userLat, $userLng)
    {
        $coffeeShops = CoffeeShop::all();
        $scores = [];
        
        foreach ($coffeeShops as $shop) {
            $score = $this->calculateScore($shop, $userCriteria, $userLat, $userLng);
            $scores[] = [
                'shop' => $shop,
                'score' => $score,
                'details' => $this->getScoreDetails($shop, $userCriteria, $userLat, $userLng)
            ];
        }
        
        // Sort by score descending
        usort($scores, function($a, $b) {
            return $b['score'] <=> $a['score'];
        });
        
        return $scores;
    }
    
    private function calculateScore($shop, $criteria, $userLat, $userLng)
    {
        $totalWeight = 0;
        $totalScore = 0;
        
        // 1. Distance score
        $distance = $this->calculateDistance($userLat, $userLng, $shop->latitude, $shop->longitude);
        $distanceScore = $this->normalizeDistance($distance, $criteria['max_distance'] ?? 5);
        $totalScore += $distanceScore * ($this->userWeight->distance_weight / 100);
        $totalWeight += ($this->userWeight->distance_weight / 100);
        
        // 2. Price score
        $priceScore = $this->calculatePriceScore($shop, $criteria['budget'] ?? 'sedang');
        $totalScore += $priceScore * ($this->userWeight->price_weight / 100);
        $totalWeight += ($this->userWeight->price_weight / 100);
        
        // 3. WiFi score
        if (isset($criteria['need_wifi']) && $criteria['need_wifi']) {
            $wifiScore = $shop->wifi ? 100 : 0;
            $totalScore += $wifiScore * ($this->userWeight->wifi_weight / 100);
            $totalWeight += ($this->userWeight->wifi_weight / 100);
        }
        
        // 4. Atmosphere score
        $atmosphereScore = $this->calculateAtmosphereScore($shop, $criteria);
        $totalScore += $atmosphereScore * ($this->userWeight->atmosphere_weight / 100);
        $totalWeight += ($this->userWeight->atmosphere_weight / 100);
        
        // 5. Facilities score
        $facilityScore = $this->calculateFacilityScore($shop, $criteria);
        $totalScore += $facilityScore * ($this->userWeight->facility_weight / 100);
        $totalWeight += ($this->userWeight->facility_weight / 100);
        
        return $totalWeight > 0 ? ($totalScore / $totalWeight) : 0;
    }
    
    private function calculateDistance($lat1, $lon1, $lat2, $lon2)
    {
        $earthRadius = 6371;
        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);
        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * asin(sqrt($a));
        return $earthRadius * $c;
    }
    
    private function normalizeDistance($distance, $maxDistance)
    {
        if ($distance <= $maxDistance) {
            return 100 * (1 - ($distance / $maxDistance));
        }
        return 0;
    }
    
    private function calculatePriceScore($shop, $budget)
    {
        $priceMap = ['murah' => 1, 'sedang' => 2, 'mahal' => 3];
        $shopPrice = $priceMap[$shop->price_range];
        $userBudget = $priceMap[$budget];
        
        if ($shopPrice <= $userBudget) {
            return 100;
        } elseif ($shopPrice == $userBudget + 1) {
            return 50;
        } else {
            return 0;
        }
    }
    
    private function calculateAtmosphereScore($shop, $criteria)
    {
        $score = 0;
        $totalCriteria = 0;
        
        if (isset($criteria['for_work']) && $criteria['for_work']) {
            $score += $shop->suitable_for_work ? 100 : 0;
            $totalCriteria++;
        }
        
        if (isset($criteria['quiet']) && $criteria['quiet']) {
            $score += $shop->quiet_atmosphere ? 100 : 0;
            $totalCriteria++;
        }
        
        if (isset($criteria['outdoor']) && $criteria['outdoor']) {
            $score += $shop->outdoor_seating ? 100 : 0;
            $totalCriteria++;
        }
        
        return $totalCriteria > 0 ? ($score / $totalCriteria) : 60; // default 60
    }
    
    private function calculateFacilityScore($shop, $criteria)
    {
        $score = 0;
        $totalCriteria = 0;
        
        $facilityMap = [
            'need_power_outlet' => 'power_outlet',
            'need_smoking_area' => 'smoking_area',
            'need_parking' => 'parking',
            'need_meeting_room' => 'meeting_room'
        ];
        
        foreach ($facilityMap as $key => $field) {
            if (isset($criteria[$key]) && $criteria[$key]) {
                $score += $shop->$field ? 100 : 0;
                $totalCriteria++;
            }
        }
        
        return $totalCriteria > 0 ? ($score / $totalCriteria) : 100;
    }
    
    private function getScoreDetails($shop, $criteria, $userLat, $userLng)
    {
        $distance = $this->calculateDistance($userLat, $userLng, $shop->latitude, $shop->longitude);
        
        return [
            'distance_km' => round($distance, 2),
            'distance_score' => $this->normalizeDistance($distance, $criteria['max_distance'] ?? 5),
            'price_score' => $this->calculatePriceScore($shop, $criteria['budget'] ?? 'sedang'),
            'total_score' => $this->calculateScore($shop, $criteria, $userLat, $userLng)
        ];
    }
}