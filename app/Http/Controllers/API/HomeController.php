<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http; // <-- Import Facade HTTP
use App\Http\Controllers\Controller; // Pastikan Controller di-import

class HomeController extends Controller
{
    // Tampilkan semua produk menggunakan API
    public function index()
    {
        // 1. Tentukan URL API Anda. 
        // Gunakan env() untuk mengambil URL dasar API
        $apiUrl = env('APP_URL') . '/api/products'; 
        
        // 2. Lakukan Request GET ke API
        $response = Http::get($apiUrl);

        // 3. Cek apakah request berhasil (status 200)
        if ($response->successful()) {
            // Ambil data produk dari body JSON
            $products = $response->json()['data']; 
            
            // Perhatikan: 'data' di sini harus sesuai dengan format respons JSON API Anda:
            /* {
                "status": true,
                "data": [...] <-- yang kita ambil
            }
            */

            // 4. Kirim data ke View
            return view('home', compact('products'));

        } else {
            // Jika gagal (misalnya 404 atau 500)
            // Anda bisa mengembalikan pesan error ke view atau array kosong
            return view('home', ['products' => [], 'error' => 'Gagal mengambil data produk dari API.']);
        }
    }

    // Tampilkan 1 produk menggunakan API
    public function show($id)
    {
        // 1. Tentukan URL API untuk produk tertentu
        $apiUrl = env('APP_URL') . '/api/products/' . $id; 
        
        // 2. Lakukan Request GET
        $response = Http::get($apiUrl);

        // 3. Cek apakah request berhasil
        if ($response->successful()) {
            $product = $response->json()['data']; 
            
            return view('product', compact('product'));

        } else if ($response->status() === 404) {
            // Handle jika produk tidak ditemukan
            abort(404, 'Produk tidak ditemukan melalui API.');
        }
        
        abort(500, 'Gagal mengambil detail produk dari API.');
    }
}