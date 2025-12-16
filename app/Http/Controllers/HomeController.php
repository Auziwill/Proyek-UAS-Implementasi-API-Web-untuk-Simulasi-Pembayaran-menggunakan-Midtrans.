<?php

namespace App\Http\Controllers;

use App\Models\Product;

class HomeController extends Controller
{
    // tampilkan semua produk
    public function index()
    {
        $products = Product::latest()->get();

        return view('home', compact('products'));
    }

    // tampilkan 1 produk
    public function show($id)
    {
        $product = Product::findOrFail($id);

        return view('product', compact('product'));
    }
}
