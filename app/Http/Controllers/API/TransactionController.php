<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class TransactionController extends Controller
{
    /**
     * GET LIST TRANSAKSI USER (POSTMAN)
     */
    public function index()
    {
        $transactions = Transaction::where('user_id', Auth::id())
            ->latest()
            ->get()
            ->map(function ($transaction) {

                // Ambil produk dari config/products.php
                $product = collect(config('products'))
                    ->firstWhere('id', $transaction->product_id);
                

                // Tambahkan relasi manual
                $transaction->product = $product;
                

                // Link pembayaran Midtrans
                $transaction->payment_url = $transaction->snap_token
                    ? 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $transaction->snap_token
                    : null;

                return $transaction;
            });

        return response()->json([
            'status' => true,
            'message' => 'Data transaksi berhasil diambil',
            'data' => $transactions
        ], 200);
    }

    /**
     * GET DETAIL TRANSAKSI BY ID
     */
     public function show($id)
    {
        $transaction = Transaction::where('id', $id)
            ->where('user_id', Auth::id()) // keamanan
            ->firstOrFail();

        // Ambil produk dari config
        $product = collect(config('products'))
            ->firstWhere('id', $transaction->product_id);

        return response()->json([
            'status' => true,
            'data' => [
                'id' => $transaction->id,
                'product' => $product['name'] ?? '-',
                'price' => $transaction->price,
                'status' => $transaction->status,
                'created_at' => $transaction->created_at->format('Y-m-d H:i:s'),
                'payment_url' => $transaction->snap_token
                    ? 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $transaction->snap_token
                    : null
            ]
        ], 200);
}
}