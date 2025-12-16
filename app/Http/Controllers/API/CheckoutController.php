<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;


class CheckoutController extends Controller
{
    /**
     * PROSES CHECKOUT (POSTMAN)
     */
   public function process(Request $request)
{
    $request->validate([
        'product_id' => 'required',
        'price' => 'required|numeric',
        'description' => 'nullable|string',
        'image' => 'nullable|string|url'
    ]);

    $user = Auth::user();

    // 1. Simpan transaksi
    $transaction = Transaction::create([
        'user_id' => $user->id,
        'product_id' => $request->product_id,
        'price' => $request->price,
        'status' => 'pending',
    ]);

    // 2. Konfigurasi Midtrans
    \Midtrans\Config::$serverKey = config('midtrans.serverKey');
    \Midtrans\Config::$isProduction = false; // sandbox
    \Midtrans\Config::$isSanitized = true;
    \Midtrans\Config::$is3ds = true;

    // 3. Parameter transaksi
    $params = [
        'transaction_details' => [
            'order_id' => 'ORDER-' . $transaction->id,
            'gross_amount' => $transaction->price,
        ],
        'customer_details' => [
            'first_name' => $user->name,
            'email' => $user->email,
        ],
    ];

    // 4. Generate Snap Token
    $snapToken = \Midtrans\Snap::getSnapToken($params);

    // 5. Simpan token
    $transaction->snap_token = $snapToken;
    $transaction->save();

    // 6. Generate PAYMENT URL
    $paymentUrl = 'https://app.sandbox.midtrans.com/snap/v2/vtweb/' . $snapToken;

    return response()->json([
        'message' => 'Checkout berhasil',
        'transaction' => $transaction,
        'snap_token' => $snapToken,
        'payment_url' => $paymentUrl
    ], 201);
}


    /**
     * SUCCESS PAYMENT (DARI CLIENT / CALLBACK)
     */
    public function success($id)
    {
        $transaction = Transaction::findOrFail($id);
        $transaction->status = 'success';
        $transaction->save();

        return response()->json([
            'message' => 'Pembayaran berhasil',
            'transaction' => $transaction
        ]);
    }
}
