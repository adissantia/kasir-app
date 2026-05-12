<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi</title>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #000;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header h2 {
            margin: 0;
        }

        .header p {
            margin: 2px 0;
            font-size: 11px;
        }

        .periode {
            margin-bottom: 15px;
            font-size: 11px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 6px;
            font-size: 11px;
        }

        table th {
            background-color: #f2f2f2;
            text-align: center;
        }

        .text-center { text-align: center; }
        .text-right { text-align: right; }

        .badge {
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 10px;
        }

        .qr { background: #dbeafe; }
        .kasir { background: #dcfce7; }

        .total {
            margin-top: 15px;
            font-weight: bold;
            text-align: right;
            font-size: 13px;
        }

        .footer {
            margin-top: 30px;
            text-align: right;
            font-size: 11px;
        }
    </style>
</head>

<body>

    <!-- HEADER -->
    <div class="header">
        <h2>LAPORAN TRANSAKSI</h2>
        <p>CAFETARIA</p>
        <p>Jl. Sriwijaya No. 123</p>
    </div>

    <!-- PERIODE -->
    <div class="periode">
        Periode :
        {{ $from ? date('d-m-Y', strtotime($from)) : '-' }}
        s/d
        {{ $to ? date('d-m-Y', strtotime($to)) : '-' }}
    </div>

    <!-- TABEL -->
    <table>
        <thead>
            <tr>
                <th width="5%">No</th>
                <th width="20%">Kode</th>
                <th width="15%">Sumber</th>
                <th width="25%">Tanggal</th>
                <th width="20%">Total</th>
            </tr>
        </thead>
        <tbody>
            @php $grandTotal = 0; @endphp

            @forelse($data as $t)
                <tr>
                    <td class="text-center">{{ $loop->iteration }}</td>

                    <td>{{ $t['kode'] }}</td>

                    <td class="text-center">
                        @if($t['sumber'] == 'QR')
                            <span class="badge qr">QR</span>
                        @else
                            <span class="badge kasir">Kasir</span>
                        @endif
                    </td>

                    <td class="text-center">
                        {{ date('d-m-Y H:i', strtotime($t['tanggal'])) }}
                    </td>

                    <td class="text-right">
                        Rp {{ number_format($t['total'], 0, ',', '.') }}
                    </td>
                </tr>

                @php $grandTotal += $t['total']; @endphp
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        Tidak ada data
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- TOTAL -->
    <div class="total">
        Total Pendapatan :
        Rp {{ number_format($grandTotal, 0, ',', '.') }}
    </div>

    <!-- FOOTER -->
    <div class="footer">
        Dicetak pada :
        {{ date('d-m-Y H:i') }} WIB
    </div>

</body>
</html>