@extends('layouts.app')

@section('content')

<div class="bg-white p-6 rounded-xl shadow">

    <!-- TITLE -->
    <h1 class="text-2xl font-bold mb-1">Laporan Transaksi</h1>
    <p class="text-gray-400 mb-5">Filter dan export laporan penjualan</p>

    <!-- FILTER -->
    <form method="GET" action="{{ route('laporan.index') }}" class="flex flex-wrap gap-3 mb-6">

        <div>
            <label class="text-sm text-gray-600">Dari</label><br>
            <input type="date" name="from"
                value="{{ request('from') }}"
                class="border px-3 py-2 rounded-lg">
        </div>

        <div>
            <label class="text-sm text-gray-600">Sampai</label><br>
            <input type="date" name="to"
                value="{{ request('to') }}"
                class="border px-3 py-2 rounded-lg">
        </div>

        <div class="flex items-end gap-2">

            <button class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600">
                Filter
            </button>

            <a href="{{ route('laporan.pdf', ['from' => request('from'), 'to' => request('to')]) }}"
                target="_blank"
                class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600">
                Export PDF
            </a>

            <a href="{{ route('laporan.excel', ['from' => request('from'), 'to' => request('to')]) }}"
                class="bg-yellow-500 text-white px-4 py-2 rounded-lg hover:bg-yellow-600">
                Export Excel
            </a>

        </div>

    </form>

    <!-- TOTAL -->
    <div class="mb-4">
        <h2 class="text-lg font-semibold">
            Total Pendapatan:
            <span class="text-green-600">
                Rp {{ number_format(collect($data)->sum('total'), 0, ',', '.') }}
            </span>
        </h2>
    </div>

    <!-- TABLE -->
    <div class="overflow-x-auto">
        <table class="w-full border border-gray-200">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">No</th>
                    <th class="p-3 border">Kode</th>
                    <th class="p-3 border">Sumber</th>
                    <th class="p-3 border">Tanggal</th>
                    <th class="p-3 border">Total</th>
                </tr>
            </thead>

            <tbody>
                @forelse($data as $item)
                <tr class="hover:bg-gray-50">

                    <td class="p-3 border text-center">
                        {{ $loop->iteration }}
                    </td>

                    <td class="p-3 border">
                        {{ $item['kode'] }}
                    </td>

                    <td class="p-3 border text-center">
                        @if($item['sumber'] == 'QR')
                            <span class="bg-blue-100 text-blue-600 px-2 py-1 rounded text-xs">
                                QR
                            </span>
                        @else
                            <span class="bg-green-100 text-green-600 px-2 py-1 rounded text-xs">
                                Kasir
                            </span>
                        @endif
                    </td>

                    <td class="p-3 border">
                        {{ \Carbon\Carbon::parse($item['tanggal'])->format('d-m-Y H:i') }}
                    </td>

                    <td class="p-3 border">
                        Rp {{ number_format($item['total'], 0, ',', '.') }}
                    </td>

                </tr>
                @empty
                <tr>
                    <td colspan="5" class="text-center p-4 text-gray-400">
                        Tidak ada data
                    </td>
                </tr>
                @endforelse
            </tbody>

        </table>
    </div>

</div>

@endsection