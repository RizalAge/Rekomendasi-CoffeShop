<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    // Tampilkan halaman login
    public function showLogin()
    {
        return view('auth.login');
    }
    
    // Proses login
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);
        
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Admin masuk ke dashboard admin
            if (Auth::user()->role === 'admin') {
                return redirect()->intended('/admin/dashboard');
            } 
            
            // SEMUA SELAIN ADMIN (User maupun Owner) langsung diarahkan ke Beranda
            return redirect()->intended('/recommendation'); 
        }
        
        return back()->withErrors([
            'email' => 'Email atau password salah.',
        ]);
    }
    
    // Tampilkan halaman register
    public function showRegister()
    {
        return view('auth.register');
    }
    
    // Proses register
    public function register(Request $request)
    {
        // Validasi dihapus untuk bagian 'role'
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);

        // Simpan user baru dengan otomatis menyetel role menjadi 'user'
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user', // Default paksa jadi user
        ]);

        return redirect()->route('login')->with('success', 'Registrasi berhasil! Silakan login dengan email dan password Anda.');
    }
    
    // Logout
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return redirect('/login');
    }
}