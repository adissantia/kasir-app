<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    protected $table = 'transaksis';
   protected $fillable = [
    'kode',
    'tanggal',
    'total'
];
    public $timestamps = false;

    public function detail()
    {
    return $this->hasMany(DetailTransaksi::class);
    }
}