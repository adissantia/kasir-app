<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Produk;
use App\Models\Order;
use App\Models\OrderDetail;
use DB;

class CustomerController extends Controller
{
    /*
    |------------------------------------------------------------------
    | MENU
    |------------------------------------------------------------------
    */
    public function menu($meja, Request $request)
{
    session(['meja' => $meja]);

    $kategori = $request->kategori;

    $query = \App\Models\Produk::query();

    if ($kategori) {
        $query->where('kategori', $kategori);
    }

    $produk = $query->get();

    return view('customer.menu', compact('produk','meja','kategori'));
}
    /*
    |------------------------------------------------------------------
    | TAMBAH CART
    |------------------------------------------------------------------
    */
    public function addCart(Request $request)
    {
        $produk = Produk::findOrFail($request->produk_id);

        $cart = session()->get('cart', []);

        if (isset($cart[$produk->id])) {
            $cart[$produk->id]['qty']++;
        } else {
            $cart[$produk->id] = [
                'id'    => $produk->id,
                'nama'  => $produk->nama_produk,
                'harga' => $produk->harga,
                'qty'   => 1
            ];
        }

        session()->put('cart', $cart);

        return back()->with('success', 'Ditambahkan ke cart');
    }

    public function removeCart(Request $request)
{
    $produk = Produk::findOrFail($request->produk_id);

    $cart = session()->get('cart', []);

    if(isset($cart[$produk->id])){

        // kurangi qty
        $cart[$produk->id]['qty']--;

        // kalau habis, hapus dari cart
        if($cart[$produk->id]['qty'] <= 0){
            unset($cart[$produk->id]);
        }

        session()->put('cart',$cart);
    }

    return back();
}

    /*
    |------------------------------------------------------------------
    | CHECKOUT
    |------------------------------------------------------------------
    */
    public function checkout()
    {
        $cart = session()->get('cart', []);

        return view('customer.checkout', compact('cart'));
    }

    /*
    |------------------------------------------------------------------
    | SIMPAN ORDER
    |------------------------------------------------------------------
    */
    public function store(Request $request)
    {
        $request->validate([
            'payment_method' => 'required|in:qris,cashier'
        ]);

        $cart  = session('cart', []);
        $meja  = session('meja');

        if (count($cart) == 0) {
            return back()->with('error', 'Keranjang kosong');
        }

        if (!$meja) {
            return back()->with('error', 'Nomor meja tidak ditemukan');
        }

        DB::beginTransaction();

        try {
            $invoice = 'INV' . date('YmdHis');
            $total   = 0;

            // hitung total
            foreach ($cart as $item) {
                $total += $item['harga'] * $item['qty'];
            }

            // simpan order
            $order = Order::create([
                'invoice'        => $invoice,
                'table_number'   => $meja,
                'payment_method' => $request->payment_method,
                'payment_status' => 'unpaid',
                'order_status'   => 'pending',
                'total'          => $total,
            ]);

            // simpan detail
            foreach ($cart as $item) {
                OrderDetail::create([
                    'order_id'  => $order->id,
                    'produk_id' => $item['id'],
                    'qty'       => $item['qty'],
                    'subtotal'  => $item['harga'] * $item['qty'],
                ]);
            }

            DB::commit();

            // bersihkan cart
            session()->forget('cart');
            session(['last_invoice' => $invoice]);

            /*
            |----------------------------------------------------------
            | 🔥 LOGIC PEMBAYARAN (INI YANG PENTING)
            |----------------------------------------------------------
            */

            if ($request->payment_method == 'qris') {
                // ke halaman QR
                return redirect('/payment-qris/' . $invoice);
            } else {
                // ke halaman struk (kasir)
                return redirect('/receipt/' . $invoice);
            }

        } catch (\Exception $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }
} 