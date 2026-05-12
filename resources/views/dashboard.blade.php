@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')

<!-- CARD ATAS -->
<div class="grid grid-cols-1 md:grid-cols-3 gap-6">

    <!-- Total Produk -->
    <div class="bg-gradient-to-r from-blue-500 to-indigo-600 text-white p-6 rounded-xl shadow hover:scale-105 transition">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm opacity-80">Total Produk</h3>
                <p class="text-3xl font-bold mt-2">{{ $totalProduk }}</p>
            </div>
            <div class="text-4xl opacity-30">📦</div>
        </div>
    </div>

    <!-- Transaksi Hari Ini -->
    <div class="bg-gradient-to-r from-purple-500 to-pink-500 text-white p-6 rounded-xl shadow hover:scale-105 transition">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm opacity-80">Transaksi Hari Ini</h3>
                <p class="text-3xl font-bold mt-2">{{ $transaksiHariIni }}</p>
            </div>
            <div class="text-4xl opacity-30">🛒</div>
        </div>
    </div>

    <!-- Stok Masuk Hari Ini -->
    <div class="bg-gradient-to-r from-emerald-500 to-teal-600 text-white p-6 rounded-xl shadow hover:scale-105 transition">
        <div class="flex justify-between items-center">
            <div>
                <h3 class="text-sm opacity-80">Stok Masuk Hari Ini</h3>
                <p class="text-3xl font-bold mt-2">{{ $stokMasukHariIni }}</p>
            </div>
            <div class="text-4xl opacity-30">📥</div>
        </div>
    </div>

</div>

<!-- SECTION BAWAH -->
<div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-6">

    <!-- GRAFIK DONUT -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-bold mb-4">🥧 Produk Terlaris</h3>

        <div class="h-64 flex justify-center items-center">
            <canvas id="chartProduk"></canvas>
        </div>
    </div>

    <!-- TOP PRODUK -->
    <div class="bg-white p-6 rounded-xl shadow">
        <h3 class="text-lg font-bold mb-4">🔥 Top Produk</h3>

        <ul>
            @foreach($produkTerlaris as $index => $item)
                <li class="flex justify-between items-center border-b py-2">
                    <span>{{ $index + 1 }}. {{ $item->nama_produk }}</span>
                    <span class="bg-gray-100 text-gray-700 px-3 py-1 rounded-full text-sm">
                        {{ $item->total }}
                    </span>
                </li>
            @endforeach
        </ul>
    </div>

</div>

<!-- CHART JS -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    const data = {
        labels: {!! json_encode($produkTerlaris->pluck('nama_produk')) !!},
        datasets: [{
            data: {!! json_encode($produkTerlaris->pluck('total')) !!},
            backgroundColor: [
                '#3b82f6',
                '#8b5cf6',
                '#10b981',
                '#f59e0b',
                '#ef4444'
            ],
            hoverOffset: 10
        }]
    };

    const config = {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    };

    new Chart(document.getElementById('chartProduk'), config);
</script>

@endsection