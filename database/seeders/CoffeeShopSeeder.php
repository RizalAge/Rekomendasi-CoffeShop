<?php

namespace Database\Seeders;

use App\Models\CoffeeShop;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str; 

class CoffeeShopSeeder extends Seeder
{
    public function run(): void
    {
        $coffeeShops = [
            [
                'name' => 'Kopi Soe - Setiabudi',
                'description' => 'Coffee shop dengan suasana vintage dan kopi lokal berkualitas',
                'address' => 'Jl. Setiabudi No. 25, Bandung',
                'latitude' => -6.877457,
                'longitude' => 107.604813,
                'price_range' => 'sedang',
                'min_price' => 25000,
                'max_price' => 50000,
                'wifi' => true,
                'power_outlet' => true,
                'smoking_area' => false,
                'outdoor_seating' => true,
                'suitable_for_work' => true,
                'quiet_atmosphere' => true,
                'manual_brew' => true,
                'open_time' => '08:00:00',
                'close_time' => '22:00:00',
                'rating_avg' => 4.5,
            ],
            [
                'name' => 'Two Cents Coffee',
                'description' => 'Coffee shop modern dengan konsep minimalis',
                'address' => 'Jl. Progo No. 12, Bandung',
                'latitude' => -6.909528,
                'longitude' => 107.612340,
                'price_range' => 'mahal',
                'min_price' => 35000,
                'max_price' => 75000,
                'wifi' => true,
                'power_outlet' => true,
                'smoking_area' => true,
                'outdoor_seating' => false,
                'suitable_for_work' => true,
                'quiet_atmosphere' => false,
                'manual_brew' => true,
                'non_coffee' => true,
                'open_time' => '07:00:00',
                'close_time' => '23:00:00',
                'rating_avg' => 4.7,
            ],
            [
                'name' => 'Kopi Nako Hegarmanah',
                'description' => 'Coffee shop dengan suasana santai',
                'address' => 'Jl. Hegarmanah No. 9, Bandung',
                'latitude' => -6.875600,
                'longitude' => 107.618900,
                'price_range' => 'sedang',
                'min_price' => 20000,
                'max_price' => 45000,
                'wifi' => true,
                'power_outlet' => true,
                'smoking_area' => true,
                'outdoor_seating' => true,
                'suitable_for_work' => true,
                'quiet_atmosphere' => false,
                'open_time' => '09:00:00',
                'close_time' => '23:00:00',
                'rating_avg' => 4.3,
            ],
        ];

        foreach ($coffeeShops as $shop) {
            $shop['slug'] = Str::slug($shop['name']);
            CoffeeShop::create($shop);
        }
        
        $this->command->info('Berhasil menambahkan ' . count($coffeeShops) . ' coffee shop!');
    }
}