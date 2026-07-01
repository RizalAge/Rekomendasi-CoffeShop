<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\CoffeeShop; 

class AdminController extends Controller
{
    // Menampilkan Dashboard Admin
    public function dashboard()
    {
        return view('admin.dashboard'); 
    }

    // Menampilkan daftar pengajuan cafe yang perlu divalidasi
    public function cekValidasi()
    {
        // Ambil semua cafe yang statusnya masih 'pending' beserta data pemiliknya (owner)
        $pendingCafes = CoffeeShop::where('status', 'pending')->with('owner')->get();
        
        // Arahkan ke file view admin/validasi.blade.php
        return view('admin.validasi', compact('pendingCafes'));
    }

    // Memproses persetujuan (approve) atau penolakan (reject)
    public function prosesValidasi(Request $request, $id, $status)
    {
        // Validasi input status dari URL
        if (!in_array($status, ['approved', 'rejected'])) {
            abort(400, 'Status tidak valid.');
        }

        // Cari cafe berdasarkan ID, update statusnya, lalu simpan ke database
        $cafe = CoffeeShop::findOrFail($id);
        $cafe->status = $status;
        $cafe->save();

        // Tentukan kata untuk pesan notifikasi
        $pesan = $status == 'approved' ? 'disetujui' : 'ditolak';

        // Redirect (kembali) ke halaman validasi dengan membawa pesan sukses
        return redirect()->route('admin.validasi.index')
                         ->with('success', "Pengajuan cafe '{$cafe->name}' berhasil {$pesan}.");
    }

    public function cafeDisetujui()
    {
        // Mengambil semua cafe dengan status 'approved'
        $approvedCafes = \App\Models\CoffeeShop::where('status', 'approved')->get();
        
        return view('admin.cafe_disetujui', compact('approvedCafes'));
    }
}