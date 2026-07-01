<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\CoffeeShop;
use Illuminate\Support\Str; 

class OwnerController extends Controller
{
    // Menampilkan Dashboard Pemilik Cafe
    public function dashboard()
    {
        $myCafes = CoffeeShop::where('user_id', Auth::id())->get();
        return view('owner.dashboard', compact('myCafes'));
    }

    // Menampilkan form upload dokumen persyaratan
    public function showUploadForm()
    {
        return view('owner.upload');
    }

    // Memproses file persyaratan yang diupload
    public function processUpload(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'nama_cafe' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', 
            'dokumen' => 'required|array', 
            'dokumen.*' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        // 2. Simpan Foto Utama ke kolom 'image'
        $mainImagePath = $request->file('image')->store('cafes', 'public');

        // 3. Simpan Dokumen Persyaratan ke array
        $docPaths = [];
        if ($request->hasFile('dokumen')) {
            foreach ($request->file('dokumen') as $file) {
                // Simpan ke folder dokumen_persyaratan
                $docPaths[] = $file->store('dokumen_persyaratan', 'public');
            }
        }

        // 4. Simpan ke Database
        CoffeeShop::create([
            'name' => $request->nama_cafe,
            'slug' => Str::slug($request->nama_cafe), 
            'image' => $mainImagePath, 
            'dokumen_persyaratan' => $docPaths,
            'user_id' => Auth::id(),
            'nama_pemilik' => $request->nama_pemilik,
            'nik_pemilik' => $request->nik_pemilik,
            'address' => $request->alamat_cafe,
            'kategori' => $request->kategori,
            'fasilitas' => $request->fasilitas,
            'status' => 'pending',
            'description' => 'Belum ada deskripsi.',
            'latitude' => 0,
            'longitude' => 0,
            'min_price' => 0,
            'max_price' => 0,
            'open_time' => '08:00:00',
            'close_time' => '22:00:00',
        ]);

        return redirect()->route('owner.dashboard')->with('success', 'Pengajuan berhasil dikirim!');
    }

    // Menampilkan halaman kelola data cafe
    public function kelolaData($id)
        {
            $cafe = CoffeeShop::where('id', $id)
                            ->where('user_id', Auth::id())
                            ->where('status', 'approved')
                            ->firstOrFail();

            return view('owner.kelola', compact('cafe'));
        }

    // Memproses update data dan fasilitas cafe
    public function updateData(Request $request)
    {
        $cafe = CoffeeShop::where('id', $request->cafe_id)
                          ->where('user_id', Auth::id())
                          ->where('status', 'approved')
                          ->firstOrFail();

        $request->validate([
            'address'    => 'nullable|string',
            'phone'      => 'nullable|string|max:20',
            'open_time'  => 'nullable',
            'close_time' => 'nullable',
            'min_price'  => 'nullable|numeric',
            'max_price'  => 'nullable|numeric',
        ]);

        $cafe->address    = $request->address;
        $cafe->phone      = $request->phone;
        $cafe->open_time  = $request->open_time;
        $cafe->close_time = $request->close_time;
        $cafe->min_price  = $request->min_price ?? 0;
        $cafe->max_price  = $request->max_price ?? 0;

        // Hitung price_range otomatis
        $minPrice = $request->min_price ?? 0;
        if ($minPrice < 25000) {
            $cafe->price_range = 'murah';
        } elseif ($minPrice <= 50000) {
            $cafe->price_range = 'sedang';
        } else {
            $cafe->price_range = 'mahal';
        }

        // Simpan fasilitas
        $cafe->wifi              = $request->has('wifi') ? 1 : 0;
        $cafe->power_outlet      = $request->has('power_outlet') ? 1 : 0;
        $cafe->smoking_area      = $request->has('smoking_area') ? 1 : 0;
        $cafe->outdoor_seating   = $request->has('outdoor_seating') ? 1 : 0;
        $cafe->quiet_atmosphere  = $request->has('quiet_atmosphere') ? 1 : 0;
        $cafe->suitable_for_work = $request->has('suitable_for_work') ? 1 : 0;

        $cafe->save();

        return redirect()->back()->with('success', 'Data dan fasilitas cafe berhasil diperbarui!');
    }
}