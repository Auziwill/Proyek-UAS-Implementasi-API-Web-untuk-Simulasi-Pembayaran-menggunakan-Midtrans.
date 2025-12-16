<?php

namespace App\Http\Controllers\Api;

use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ProductController extends Controller
{
    /**
     * GET ALL PRODUCTS
     */
    public function index()
    {
        return response()->json([
            'status' => true,
            'data' => Product::all()
        ]);
    }

    /**
     * GET ONE PRODUCT
     */
    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $product
        ]);
    }

    /**
     * CREATE PRODUCT
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required|numeric',
        'description' => 'nullable|string', // Tambah validasi description
        'image' => 'nullable|string|url|' // Validasi untuk file foto
            
        ]);

        $product = Product::create($request->only('name', 'price', 'description', 'image'));

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil ditambahkan',
            'data' => $product
            
        ], 201);
    }

    /**
     * UPDATE PRODUCT (PUT)
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required',
            'price' => 'sometimes|required|numeric',
            'description' => 'nullable|string', // Tambah validasi description
         'image' => 'nullable|string|url|max:2048' // Validasi untuk file foto
        ]);

        $product->update($request->only('name', 'price', 'description', 'image'));

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil diubah',
            'data' => $product
        ]);
    }

    /**
     * DELETE PRODUCT
     */
    public function destroy($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Produk tidak ditemukan'
            ], 404);
        }

        $product->delete();

        return response()->json([
            'status' => true,
            'message' => 'Produk berhasil dihapus'
        ]);
    }
}
