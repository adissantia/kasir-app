<!DOCTYPE html>
<html>
<head>
    <title>Pesanan Customer</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f3f4f6;
            padding: 30px;
        }

        .container {
            background: #ffffff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0,0,0,.08);
        }

        h2 {
            margin: 0;
        }

        .top-bar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }

        .btn {
            padding: 8px 14px;
            border-radius: 6px;
            text-decoration: none;
            font-size: 14px;
            border: none;
            cursor: pointer;
        }

        .btn-dashboard {
            background: #2563eb;
            color: white;
        }

        .btn-paid {
            background: #16a34a;
            color: white;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 10px;
            vertical-align: top;
        }

        th {
            background: #f9fafb;
        }

        .badge {
            padding: 4px 10px;
            border-radius: 12px;
            font-size: 12px;
            font-weight: bold;
            display: inline-block;
        }

        .paid {
            background: #dcfce7;
            color: #166534;
        }

        .unpaid {
            background: #fee2e2;
            color: #991b1b;
        }

        .pending {
            background: #fde68a;
            color: #92400e;
        }

        .diproses {
            background: #bfdbfe;
            color: #1e40af;
        }

        .siap {
            background: #ddd6fe;
            color: #5b21b6;
        }

        .diantar, .selesai {
            background: #bbf7d0;
            color: #166534;
        }

        .alert {
            padding: 10px;
            border-radius: 6px;
            margin-bottom: 15px;
            background: #dcfce7;
            color: #166534;
        }

        select {
            margin-top: 6px;
            padding: 6px 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
            font-size: 13px;
            width: 100%;
            cursor: pointer;
        }
    </style>
</head>

<body>

<div class="container">

    <div class="top-bar">
        <h2>📋 Daftar Pesanan Customer</h2>
        <a href="/dashboard" class="btn btn-dashboard">⬅ Kembali ke Dashboard</a>
    </div>

    @if(session('success'))
        <div class="alert">
            {{ session('success') }}
        </div>
    @endif

    <table>
        <tr>
            <th>Invoice</th>
            <th>Meja</th>
            <th>Detail Pesanan</th>
            <th>Total</th>
            <th>Pembayaran</th>
            <th>Status Order</th>
            <th>Aksi</th>
        </tr>

        @forelse($orders as $order)
        <tr>

            <td>{{ $order->invoice }}</td>

            <td>{{ $order->table_number }}</td>

            <td>
                @foreach($order->details as $d)
                    - {{ $d->produk->nama_produk }}
                    ({{ $d->qty }}x)
                    = Rp{{ number_format($d->subtotal) }}
                    <br>
                @endforeach
            </td>

            <td><b>Rp{{ number_format($order->total) }}</b></td>

            <td>
                <span class="badge {{ $order->payment_status }}">
                    {{ strtoupper($order->payment_status) }}
                </span>
            </td>

            <td>
                <span class="badge {{ $order->order_status }}">
                    {{ strtoupper($order->order_status) }}
                </span>

                @if($order->payment_status == 'paid')
                <form method="POST" action="{{ route('orders.updateStatus', $order->id) }}">
                    @csrf
                    @method('PUT')

                    <select name="order_status" onchange="this.form.submit()">
                        <option disabled selected>Ubah Status</option>
                        <option value="pending">Pending</option>
                        <option value="diproses">Diproses</option>
                        <option value="siap">Siap</option>
                        <option value="diantar">Diantar</option>
                        <option value="selesai">Selesai</option>
                    </select>
                </form>
                @endif
            </td>

            <td>
                @if($order->payment_status == 'unpaid')
                <form method="POST" action="{{ route('orders.paid', $order->id) }}">
                    @csrf
                    <button class="btn btn-paid">Konfirmasi Paid</button>
                </form>
                @else
                    ✔
                @endif
            </td>

        </tr>
        @empty
        <tr>
            <td colspan="7" align="center">
                Belum ada pesanan
            </td>
        </tr>
        @endforelse
    </table>

</div>

</body>
</html>