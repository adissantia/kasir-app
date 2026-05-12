<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;

class OrderController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | LIST PESANAN CUSTOMER (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $orders = Order::with('details.produk')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('orders.index', compact('orders'));
    }


    /*
    |--------------------------------------------------------------------------
    | UPDATE STATUS ORDER (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function updateStatus(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $order->order_status = $request->order_status;
        $order->save();

        return back()->with('success', 'Status pesanan diperbarui');
    }


    /*
    |--------------------------------------------------------------------------
    | KONFIRMASI PEMBAYARAN (ADMIN)
    |--------------------------------------------------------------------------
    */
    public function markPaid($id)
    {
        $order = Order::findOrFail($id);

        if ($order->payment_status === 'paid') {
            return back()->with('error', 'Pesanan sudah dibayar');
        }

        $order->update([
            'payment_status' => 'paid',
            'order_status'   => 'diproses',
        ]);

        return back()->with('success', 'Pembayaran berhasil dikonfirmasi');
    }


    /*
    |--------------------------------------------------------------------------
    | STRUK CUSTOMER
    |--------------------------------------------------------------------------
    */
    public function receipt($invoice)
    {
        $order = Order::with('details.produk')
            ->where('invoice', $invoice)
            ->first();

        if (!$order) {
            abort(404, 'Invoice tidak ditemukan');
        }

        /*
        🔥 LOGIC PENTING:

        - QRIS → harus sudah PAID baru boleh lihat struk
        - KASIR → boleh lihat walaupun UNPAID
        */

        if (
            $order->payment_method == 'qris' &&
            $order->payment_status !== 'paid'
        ) {
            abort(403, 'Menunggu verifikasi pembayaran');
        }

        return view('orders.receipt', compact('order'));
    }
}