<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailTransaksi extends Model
{
    use HasFactory;

    protected $table = 'detail_transaksis';
    public $timestamps = false;

    protected $fillable = [
        'transaksi_id',
        'produk_id',
        'qty',
        'subtotal',
    ];

    // RELASI KE PRODUK
    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // RELASI KE TRANSAKSI
    public function transaksi()
    {
        return $this->belongsTo(Transaksi::class);
    }
}