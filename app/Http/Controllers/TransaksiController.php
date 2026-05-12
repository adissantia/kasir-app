<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use App\Models\DetailTransaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TransaksiController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | HALAMAN KASIR (OFFLINE)
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $produks = Produk::all();

        // cart khusus kasir
        $cart = session()->get('cart_kasir', []);

        $transaksi = null;

        // ambil transaksi terakhir untuk struk
        if (session()->has('last_transaksi')) {
            $transaksi = Transaksi::with('detail.produk')
                ->find(session('last_transaksi'));
        }

        return view(
            'transaksi.index',
            compact('produks', 'cart', 'transaksi')
        );
    }

    /*
    |--------------------------------------------------------------------------
    | TAMBAH ITEM KE CART KASIR
    |--------------------------------------------------------------------------
    */
    public function tambah(Request $request)
    {
        $request->validate([
            'produk_id' => 'required|exists:produks,id',
            'qty'       => 'required|numeric|min:1',
        ]);

        $produk = Produk::findOrFail($request->produk_id);

        $cart = session()->get('cart_kasir', []);

        $cart[] = [
            'produk_id' => $produk->id,
            'nama'      => $produk->nama_produk,
            'harga'     => $produk->harga,
            'qty'       => $request->qty,
            'subtotal'  => $produk->harga * $request->qty,
        ];

        session()->put('cart_kasir', $cart);

        return redirect()->back();
    }

    /*
    |--------------------------------------------------------------------------
    | SIMPAN TRANSAKSI + TAMPIL STRUK
    |--------------------------------------------------------------------------
    */
    public function store()
    {
        $cart = session()->get('cart_kasir');

        if (!$cart || count($cart) === 0) {
            return redirect()->back()
                ->with('error', 'Keranjang kosong');
        }

        DB::beginTransaction();

        try {
            $transaksi = Transaksi::create([
                'kode'    => 'TRX-' . date('YmdHis'),
                'tanggal' => now(),
                'total'   => 0,
            ]);

            $total = 0;

            foreach ($cart as $item) {
                DetailTransaksi::create([
                    'transaksi_id' => $transaksi->id,
                    'produk_id'    => $item['produk_id'],
                    'qty'          => $item['qty'],
                    'subtotal'     => $item['subtotal'],
                ]);

                $total += $item['subtotal'];
            }

            $transaksi->update([
                'total' => $total
            ]);

            DB::commit();

            // simpan transaksi terakhir untuk struk
            session()->put('last_transaksi', $transaksi->id);

            // kosongkan cart kasir
            session()->forget('cart_kasir');

            return redirect()->route('transaksi.index')
                ->with('success', 'Transaksi berhasil disimpan');

        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()
                ->with('error', 'Gagal menyimpan transaksi');
        }
    }

    /*
    |--------------------------------------------------------------------------
    | HAPUS ITEM DETAIL (OPSIONAL)
    |--------------------------------------------------------------------------
    */
    public function hapusDetail($id)
    {
        $detail = DetailTransaksi::findOrFail($id);

        $transaksi_id = $detail->transaksi_id;

        $detail->delete();

        // update total transaksi
        $total = DetailTransaksi::where('transaksi_id', $transaksi_id)
            ->sum('subtotal');

        Transaksi::where('id', $transaksi_id)
            ->update(['total' => $total]);

        return redirect()->back()
            ->with('success', 'Item berhasil dihapus');
    }
}