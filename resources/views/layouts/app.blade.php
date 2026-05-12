<!DOCTYPE html>
<html>
<head>
    <title>Kasir App</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="flex bg-gray-100">

<!-- SIDEBAR -->
<div class="w-64 bg-gray-800 text-white min-h-screen p-4">
    <h2 class="text-xl font-bold mb-6">Bellissimo</h2>

    <ul>
    <li class="mb-3">
        <a href="/dashboard" class="block p-2 hover:bg-gray-700 rounded">Dashboard</a>
    </li>

    <li class="mb-3">
        <a href="/produk" class="block p-2 hover:bg-gray-700 rounded">Produk</a>
    </li>

    <li class="mb-3">
        <a href="/transaksi" class="block p-2 hover:bg-gray-700 rounded">Transaksi</a>
    </li>



    <li class="mb-3">
    @include('components.order-notification')
    </li>

    <li class="mb-3">
        <a href="{{ route('laporan.index') }}" class="block p-2 hover:bg-gray-700 rounded">
    Laporan
</a>
    </li>

    <li class="mt-6">
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button class="w-full text-left p-2 hover:bg-red-600 rounded">
                Logout
            </button>
        </form>
    </li>
</ul>
</div>

<!-- CONTENT -->
<div class="flex-1">

    <!-- TOPBAR -->
    <div class="bg-white shadow p-4 flex justify-between items-center">
        <h1 class="text-lg font-semibold">Dashboard</h1>

        <div class="flex items-center space-x-4">
            <span class="text-gray-700">
                👤 {{ Auth::user()->name }}
            </span>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="bg-red-500 text-white px-3 py-1 rounded hover:bg-red-600">
                    Logout
                </button>
            </form>
        </div>
    </div>

    <!-- MAIN CONTENT -->
    <div class="p-6">
        @yield('content')
    </div>

</div>