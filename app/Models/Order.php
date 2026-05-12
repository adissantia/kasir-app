<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
    'invoice',
    'table_number',
    'payment_method',
    'payment_status',
    'order_status',
    'total'
];
    public function details()
{
    return $this->hasMany(OrderDetail::class);
}
}