@extends('layouts.app')

@section('content')

<h1 class="text-2xl font-bold mb-4">🛒 Kasir</h1>

{{-- NOTIF --}}
@if(session('success'))
<div class="bg-green-100 text-green-700 p-3 rounded mb-4">
    {{ session('success') }}
</div>
@endif

{{-- FORM --}}
<div class="bg-white p-6 rounded-xl shadow mb-6">

    <form action="{{ route('transaksi.tambah') }}" method="POST">
        @csrf

        <div class="grid grid-cols-3 gap-3 mb-4">

            <select name="produk_id" class="border p-2 rounded w-full" required>
                <option value="">Pilih Produk</option>
                @foreach($produks as $p)
                    <option value="{{ $p->id }}">
                        {{ $p->nama_produk }} - Rp {{ number_format($p->harga) }}
                    </option>
                @endforeach
            </select>

            <input type="number" name="qty" min="1" placeholder="Qty"
                   class="border p-2 rounded w-full" required>

            <button class="bg-blue-500 text-white rounded px-4">
                + Tambah
            </button>

        </div>
    </form>

    {{-- KERANJANG --}}
    <table class="w-full border text-sm mb-3">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-2">Produk</th>
                <th class="p-2">Qty</th>
                <th class="p-2">Harga</th>
                <th class="p-2">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp

            @forelse($cart as $item)
                <tr class="border-t text-center">
                    <td class="p-2">{{ $item['nama'] }}</td>
                    <td class="p-2">{{ $item['qty'] }}</td>
                    <td class="p-2">Rp {{ number_format($item['harga']) }}</td>
                    <td class="p-2">Rp {{ number_format($item['subtotal']) }}</td>
                </tr>
                @php $grandTotal += $item['subtotal']; @endphp
            @empty
                <tr>
                    <td colspan="4" class="text-center p-3 text-gray-500">
                        Keranjang kosong
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="text-right font-bold mb-3">
        Total: Rp {{ number_format($grandTotal) }}
    </div>

    <form action="{{ route('transaksi.store') }}" method="POST">
        @csrf
        <button class="bg-green-500 text-white px-4 py-2 rounded">
            💾 Simpan Transaksi
        </button>
    </form>
</div>

{{-- STRUK --}}
@if($transaksi)
<div class="bg-white p-6 rounded-xl shadow mb-6">

    <h2 class="text-xl font-bold mb-4 flex justify-between items-center">
        🧾 Detail Transaksi

        <button onclick="printStruk()"
            class="bg-blue-600 text-white px-4 py-2 rounded">
            🖨 Cetak Struk
        </button>
    </h2>

    <div id="struk-area" class="struk">

        <!-- HEADER -->
        <center>
            <b>CAFETARIA</b><br>
            Jl. Sriwijaya No. 123<br>
            Telp: 0812-3456-7890
        </center>

        <hr>

        <!-- INFO -->
        Kode : {{ $transaksi->kode }} <br>
        {{ date('d-m-Y H:i', strtotime($transaksi->tanggal)) }} WIB

        <hr>

        <!-- DETAIL -->
        @foreach($transaksi->detail as $d)

            <div class="struk-row">
                <span>{{ $d->produk->nama_produk }}</span>
                <span>Rp {{ number_format($d->subtotal) }}</span>
            </div>

            <div class="struk-sub">
                {{ $d->qty }} x {{ number_format($d->harga) }}
            </div>

        @endforeach

        <hr>

        <!-- TOTAL -->
        <div class="struk-row total">
            <span>TOTAL</span>
            <span>Rp {{ number_format($transaksi->total) }}</span>
        </div>

        <hr>

        <!-- FOOTER -->
        <center>
            Terima kasih 🙏<br>
            Barang tidak dapat dikembalikan
        </center>

    </div>
</div>
@endif

{{-- STYLE STRUK --}}
<style>
.struk {
    width: 260px;
    margin: auto;
    font-family: monospace;
    font-size: 12px;
}

.struk-row {
    display: flex;
    justify-content: space-between;
    margin-top: 4px;
}

.struk-sub {
    font-size: 11px;
    margin-bottom: 6px;
}

.total {
    font-weight: bold;
    font-size: 13px;
}

hr {
    border-top: 1px dashed #000;
    margin: 6px 0;
}

@media print {
    body {
        margin: 0;
    }
}
</style>

{{-- SCRIPT PRINT --}}
<script>
function printStruk() {
    let isi = document.getElementById('struk-area').innerHTML;

    let win = window.open('', '', 'width=300,height=600');

    win.document.write(`
        <html>
        <head>
            <title>Struk</title>
            <style>
                body {
                    font-family: monospace;
                    font-size: 12px;
                    width: 260px;
                    margin: auto;
                }
                .struk-row {
                    display: flex;
                    justify-content: space-between;
                }
                .struk-sub {
                    font-size: 11px;
                }
                hr {
                    border-top: 1px dashed #000;
                }
            </style>
        </head>
        <body>
            ${isi}
        </body>
        </html>
    `);

    win.document.close();

    setTimeout(() => {
        win.print();
        win.close();
    }, 500);
}
</script>

@endsection