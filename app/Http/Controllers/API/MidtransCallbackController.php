<?php

namespace App\Http\Controllers\Api;

use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MidtransCallbackController extends Controller
{
    public function handle(Request $request)
    {
        // Ambil data dari Midtrans
        $orderId = $request->order_id;
        $transactionStatus = $request->transaction_status;
        $fraudStatus = $request->fraud_status ?? null;

        // Ambil ID transaksi dari ORDER-x
        $transactionId = str_replace('ORDER-', '', $orderId);

        $transaction = Transaction::find($transactionId);
        if (!$transaction) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        // LOGIKA STATUS
        if ($transactionStatus == 'capture' || $transactionStatus == 'settlement') {
            $transaction->status = 'success';
        } elseif ($transactionStatus == 'pending') {
            $transaction->status = 'pending';
        } elseif (in_array($transactionStatus, ['deny', 'cancel', 'expire'])) {
            $transaction->status = 'failed';
        }

        $transaction->save();

        return response()->json(['message' => 'Callback processed']);
    }
}
