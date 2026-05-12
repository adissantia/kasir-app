<!DOCTYPE html>
<html>
<head>
    <title>Struk Pesanan</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 30px;
        }

        .receipt {
            max-width: 400px;
            margin: auto;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 10px rgba(0,0,0,.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 10px;
        }

        .center {
            text-align: center;
        }

        .line {
            border-top: 1px dashed #ccc;
            margin: 10px 0;
        }

        .item {
            font-size: 14px;
            margin-bottom: 6px;
        }

        .total {
            font-size: 18px;
            font-weight: bold;
            text-align: right;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 10px;
            font-size: 12px;
            font-weight: bold;
        }

        .paid {
            background: #dcfce7;
            color: #166534;
        }

        .unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        .alert {
            margin-top: 10px;
            padding: 10px;
            border-radius: 8px;
            font-size: 13px;
        }

        .kasir {
            background: #fff3cd;
            color: #856404;
        }

        .qris {
            background: #e0f2fe;
            color: #075985;
        }

        .btn-print {
            margin-top: 15px;
            width: 100%;
            padding: 10px;
            border: none;
            background: #2563eb;
            color: white;
            border-radius: 6px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<div class="receipt">

    <h1>🧾 STRUK</h1>

    <p class="center">
        <b>Kode Pesanan:</b><br>
        {{ $order->invoice }}
    </p>

    <p><b>Meja:</b> {{ $order->table_number }}</p>

    <div class="line"></div>

    <h3>Detail:</h3>

    @foreach($order->details as $d)
        <div class="item">
            {{ $d->produk->nama_produk }}<br>
            {{ $d->qty }} x Rp{{ number_format($d->subtotal / $d->qty) }}
            = Rp{{ number_format($d->subtotal) }}
        </div>
    @endforeach

    <div class="line"></div>

    <div class="total">
        Total: Rp{{ number_format($order->total) }}
    </div>

    <div class="line"></div>

    <p>
        <b>Pembayaran:</b>
        {{ strtoupper($order->payment_method) }}
    </p>

    <p>
        <b>Status:</b>
        <span class="badge {{ $order->payment_status }}">
            {{ strtoupper($order->payment_status) }}
        </span>
    </p>

    {{-- 🔥 PESAN KHUSUS --}}
    @if($order->payment_method == 'cashier')
        <div class="alert kasir">
            ⚠️ Silakan menuju kasir dan tunjukkan kode pesanan ini untuk pembayaran.
        </div>
    @endif

    @if($order->payment_method == 'qris' && $order->payment_status == 'unpaid')
        <div class="alert qris">
            📱 Silakan lakukan pembayaran QRIS dan tunggu konfirmasi admin.
        </div>
    @endif

    <div class="line"></div>

    <p class="center">
        Terima kasih 🙏<br>
        Pesanan akan segera diproses
    </p>

    <button onclick="window.print()" class="btn-print">
        🖨️ Simpan Struk
    </button>

</div>

</body>
</html>