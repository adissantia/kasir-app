<?php

namespace App\Http\Controllers;

use App\Models\Produk;
use App\Models\Transaksi;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProduk = Produk::count();

        $transaksiHariIni = Transaksi::whereDate(
            'tanggal',
            Carbon::today()
        )->count();

        $stokMasukHariIni = Produk::whereDate(
            'created_at',
            Carbon::today()
        )->sum('stok');

        // ✅ FIX NAMA TABEL DI SINI
        $produkTerlaris = DB::table('detail_transaksis')
            ->join(
                'produks',
                'detail_transaksis.produk_id',
                '=',
                'produks.id'
            )
            ->select(
                'produks.nama_produk',
                DB::raw('SUM(detail_transaksis.qty) as total')
            )
            ->groupBy('produks.nama_produk')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return view('dashboard', compact(
            'totalProduk',
            'transaksiHariIni',
            'stokMasukHariIni',
            'produkTerlaris'
        ));
    }
}