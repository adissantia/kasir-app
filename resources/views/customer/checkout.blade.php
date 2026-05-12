<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Keranjang</title>

<script src="https://cdn.tailwindcss.com"></script>

<style>
body{
background:#F0F0EC;
}

.dark-btn{
background:#D0D0CB;
}
</style>

</head>
<body>

<div class="max-w-md mx-auto min-h-screen">

<!-- HEADER -->
<div class="bg-[#7F796A] text-white p-5 rounded-b-3xl">

<div class="flex items-center gap-3">
<a href="{{ route('customer.menu',session('meja')) }}"
class="text-2xl">
←
</a>

<div>
<h2 class="text-xl font-bold">
Keranjang
</h2>

<p class="text-sm text-gray-300">
Meja {{ session('meja') }}
</p>
</div>

</div>

</div>



<div class="p-4">

@if(session('error'))
<div class="bg-red-100 text-red-700 p-3 rounded-xl mb-4">
{{ session('error') }}
</div>
@endif


@php
$subtotal = 0;
@endphp


@if(empty($cart) || count($cart)==0)

<div class="text-center mt-20 text-gray-500">
Keranjang kosong
</div>

@else


<!-- PESANAN -->
<div class="bg-white rounded-3xl p-5 mb-4 shadow-sm">

<h3 class="uppercase text-xs tracking-widest text-gray-500 mb-4">
Pesanan Kamu
</h3>


@foreach($cart as $item)

@if(is_array($item) && isset($item['nama']))

@php
$itemTotal = $item['harga'] * $item['qty'];
$subtotal += $itemTotal;
@endphp


<div class="flex justify-between items-center mb-4">

<div>
<h4 class="font-semibold">
{{ $item['nama'] }}
</h4>

<p class="text-sm text-gray-500">
x{{ $item['qty'] }}
</p>
</div>

<div class="font-semibold">
Rp {{ number_format($itemTotal,0,',','.') }}
</div>

</div>

@endif
@endforeach


<hr class="my-4">


@php
$total = $subtotal;
@endphp


<div class="space-y-2 text-sm">

<div class="flex justify-between text-xl font-bold">
<span>Total</span>

<span>
Rp {{ number_format($total,0,',','.') }}
</span>
</div>

</div>

</div>




<form action="{{ route('customer.store') }}" method="POST">
@csrf


<!-- CATATAN -->
<div class="bg-white rounded-3xl p-5 mb-4">

<h3 class="uppercase text-xs tracking-widest text-gray-500 mb-3">
Catatan
</h3>

<input
type="text"
name="catatan"
placeholder="Tulis permintaan khusus..."
class="w-full border rounded-2xl px-4 py-3 text-sm"
/>

</div>




<!-- METODE PEMBAYARAN -->
<div class="bg-white rounded-3xl p-4 mb-5">

<h3 class="uppercase text-xs tracking-widest text-gray-500 mb-3">
Metode Pembayaran
</h3>



<!-- QRIS -->
<div class="mb-3">

<input
type="radio"
name="payment_method"
value="qris"
id="qris"
checked
required
class="peer hidden"
>

<label
for="qris"
class="flex justify-between items-center border-2 border-gray-300 rounded-2xl px-4 py-3 cursor-pointer peer-checked:border-[#53635C]"
>

<div>
<h4 class="font-semibold text-sm">
QRIS
</h4>

<p class="text-xs text-gray-500">
GoPay, OVO, Dana, semua bank
</p>
</div>

<div class="w-6 h-6 rounded-full border-2 flex items-center justify-center">
<span class="peer-checked:block hidden text-xs">
✓
</span>
</div>

</label>

</div>




<!-- KASIR -->
<div>

<input
type="radio"
name="payment_method"
value="cashier"
id="cashier"
class="peer hidden"
>

<label
for="cashier"
class="flex justify-between items-center border-2 border-gray-300 rounded-2xl px-4 py-3 cursor-pointer peer-checked:border-black"
>

<div>
<h4 class="font-semibold text-sm">
Kasir
</h4>

<p class="text-xs text-gray-500">
Debit / Tunai • Bayar di kasir
</p>
</div>

<div class="w-6 h-6 rounded-full border-2 flex items-center justify-center">
<span class="peer-checked:block hidden text-xs">
✓
</span>
</div>

</label>

</div>


</div>




<button
type="submit"
class="w-full dark-btn text-black py-4 rounded-2xl text-lg font-semibold shadow"
>
Pesan Sekarang • Rp {{ number_format($total,0,',','.') }}
</button>

</form>


<p class="text-center text-sm text-gray-500 mt-4 mb-10">
Pesanan dikonfirmasi setelah pembayaran
</p>

@endif

</div>

</div>

</body>
</html>