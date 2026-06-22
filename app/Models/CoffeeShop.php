<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoffeeShop extends Model
{
    protected $fillable = [
        'name',
        'slug', 
        'description',
        'address',
        'latitude',
        'longitude',
        'price_range',
        'min_price',
        'max_price',
        'wifi',
        'power_outlet',
        'smoking_area',
        'outdoor_seating',
        'suitable_for_work',
        'quiet_atmosphere',
        'manual_brew',
        'non_coffee',
        'open_time',
        'close_time',
        'rating_avg',
        'user_id',
        'status',
        'dokumen_persyaratan',
        'nama_pemilik',
        'nik_pemilik',
        'alamat_cafe',
        'kategori',
        'fasilitas',
    ];

    /**
     * The attributes that should be cast to native types.
     * WAJIB DITAMBAHKAN agar array kategori & fasilitas bisa otomatis jadi JSON di database.
     */
    protected $casts = [
        'kategori' => 'array',
        'fasilitas' => 'array',
        'dokumen_persyaratan' => 'array',
    ];

    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}