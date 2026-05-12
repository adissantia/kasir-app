<?php

use Illuminate\Support\Facades\Route;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

use App\Http\Controllers\CustomerController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProdukController;
use App\Http\Controllers\TransaksiController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\DashboardController;


/*
|--------------------------------------------------------------------------
| ROOT
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return redirect('/login');
});


/*
|--------------------------------------------------------------------------
| SCAN QR -> SIMPAN MEJA -> MENU
|--------------------------------------------------------------------------
*/

Route::get('/scan/{meja}', function ($meja) {

    session([
        'meja' => $meja
    ]);

    return redirect("/customer/menu/$meja");

});


/*
|--------------------------------------------------------------------------
| CUSTOMER
|--------------------------------------------------------------------------
*/

Route::prefix('customer')->group(function () {

    Route::get('/menu/{meja}',
        [CustomerController::class,'menu']
    )->name('customer.menu');


    Route::post('/add-cart',
        [CustomerController::class,'addCart']
    )->name('customer.addCart');


    Route::post('/remove-cart',
        [CustomerController::class,'removeCart']
    )->name('customer.removeCart');


    Route::get('/checkout',
        [CustomerController::class,'checkout']
    )->name('customer.checkout');


    Route::post('/order',
        [CustomerController::class,'store']
    )->name('customer.store');

});


/*
|--------------------------------------------------------------------------
| QR PER MEJA
|--------------------------------------------------------------------------
*/

Route::get('/qr/{meja}', function($meja){

    return QrCode::size(300)->generate(
        url("/scan/$meja")
    );

});


/*
|--------------------------------------------------------------------------
| CLEAR CART DEBUG
|--------------------------------------------------------------------------
*/

Route::get('/clear-cart', function(){

    session()->forget('cart');
    session()->forget('meja');

    return 'Cart cleared';

});



/*
|--------------------------------------------------------------------------
| QRIS PAYMENT PAGE
|--------------------------------------------------------------------------
*/

Route::get('/payment-qris/{invoice}', function($invoice){

return "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width,initial-scale=1'>

<title>Pembayaran QRIS</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4efea;
}

.header{
background:#1f1816;
color:white;
padding:35px 25px;
border-radius:0 0 30px 30px;
}

.wrap{
max-width:420px;
margin:auto;
}

.card{
background:white;
margin:25px 20px;
padding:30px;
border-radius:30px;
box-shadow:0 2px 10px rgba(0,0,0,.08);
text-align:center;
}

.qr{
width:240px;
margin:20px auto;
display:block;
}

.invoice{
background:#f7f7f7;
padding:12px;
border-radius:15px;
margin-top:15px;
font-weight:bold;
}

.btn{
display:block;
background:#1f1816;
color:white;
text-decoration:none;
padding:16px;
border-radius:18px;
margin-top:25px;
font-weight:bold;
}

.note{
color:#777;
font-size:14px;
margin-top:15px;
}

</style>

</head>

<body>

<div class='wrap'>

<div class='header'>
<h2>Pembayaran QRIS</h2>
<p>Scan untuk menyelesaikan pembayaran</p>
</div>

<div class='card'>

<h3>Invoice</h3>

<div class='invoice'>
$invoice
</div>

<img
class='qr'
src='https://api.qrserver.com/v1/create-qr-code/?size=300x300&data=PAYMENT-$invoice'
>

<p class='note'>
Support semua e-wallet & mobile banking
</p>

<a href='/payment-wait/$invoice' class='btn'>
Saya Sudah Bayar
</a>

</div>

</div>

</body>
</html>
";

})->name('payment.qris');



/*
|--------------------------------------------------------------------------
| WAIT ADMIN CONFIRM|--------------------------------------------------------------------------
*/

Route::get('/payment-wait/{invoice}', function($invoice){

return "
<!DOCTYPE html>
<html>
<head>
<meta charset='UTF-8'>
<meta name='viewport' content='width=device-width,initial-scale=1'>

<title>Menunggu Konfirmasi</title>

<style>

body{
margin:0;
font-family:Arial;
background:#f4efea;
}

.wrap{
max-width:420px;
margin:auto;
padding-top:100px;
}

.card{
background:white;
margin:20px;
padding:35px;
border-radius:30px;
text-align:center;
box-shadow:0 2px 10px rgba(0,0,0,.08);
}

.btn{
display:inline-block;
background:#1f1816;
color:white;
text-decoration:none;
padding:14px 30px;
border-radius:18px;
margin-top:20px;
font-weight:bold;
}

</style>

</head>

<body>

<div class='wrap'>

<div class='card'>

<h2>⏳ Menunggu Konfirmasi Admin</h2>

<p>
Pembayaran Anda sedang diverifikasi.
Silakan tunggu admin mengkonfirmasi.
</p>

<a href='/check-status/$invoice' class='btn'>
Cek Status
</a>

</div>

</div>

</body>
</html>
";

})->name('payment.wait');



/*
|--------------------------------------------------------------------------
| CHECK STATUS
|--------------------------------------------------------------------------
*/

Route::get('/check-status/{invoice}', function($invoice){

$order = \App\Models\Order::where(
'invoice',
$invoice
)->first();

if(!$order){
abort(404);
}

if($order->payment_status=='paid'){
return redirect('/receipt/'.$invoice);
}

return redirect('/payment-wait/'.$invoice);

})->name('payment.status');



/*
|--------------------------------------------------------------------------
| RECEIPT
|--------------------------------------------------------------------------
*/

Route::get('/receipt/{invoice}',
    [OrderController::class,'receipt']
)->name('orders.receipt');



/*
|--------------------------------------------------------------------------
| ADMIN
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function(){


    Route::get('/dashboard',
        [DashboardController::class,'index']
    )->name('dashboard');



    Route::resource(
        'produk',
        ProdukController::class
    );



    Route::get('/transaksi',
        [TransaksiController::class,'index']
    )->name('transaksi.index');


    Route::post('/transaksi/tambah',
        [TransaksiController::class,'tambah']
    )->name('transaksi.tambah');


    Route::post('/transaksi/simpan',
        [TransaksiController::class,'store']
    )->name('transaksi.store');


    Route::delete('/transaksi/detail/{id}',
        [TransaksiController::class,'hapusDetail']
    )->name('transaksi.detail.delete');



    Route::get('/orders',
        [OrderController::class,'index']
    )->name('orders.index');


    Route::post('/orders/{id}/paid',
        [OrderController::class,'markPaid']
    )->name('orders.paid');


    Route::put('/orders/{id}/status',
        [OrderController::class,'updateStatus']
    )->name('orders.updateStatus');



    Route::get('/laporan',
        [LaporanController::class,'index']
    )->name('laporan.index');


    Route::get('/laporan/pdf',
        [LaporanController::class,'pdf']
    )->name('laporan.pdf');


    Route::get('/laporan/excel',
        [LaporanController::class,'excel']
    )->name('laporan.excel');

});


/*
|--------------------------------------------------------------------------
| AUTH
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';