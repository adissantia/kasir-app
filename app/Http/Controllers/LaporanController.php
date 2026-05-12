<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Order;
use Barryvdh\DomPDF\Facade\Pdf;

class LaporanController extends Controller
{
    /*
    |--------------------------------------------------------
    | HALAMAN LAPORAN (GABUNG KASIR + QR)
    |--------------------------------------------------------
    */
    public function index(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        // ======================
        // TRANSAKSI KASIR
        // ======================
        $transaksiQuery = Transaksi::query();

        if ($from && $to) {
            $transaksiQuery->whereBetween('tanggal', [
                $from . ' 00:00:00',
                $to . ' 23:59:59'
            ]);
        }

        $transaksi = $transaksiQuery->get()->map(function ($item) {
            return [
                'kode'   => $item->kode,
                'tanggal'=> $item->tanggal,
                'total'  => $item->total,
                'sumber' => 'Kasir'
            ];
        });

        // ======================
        // ORDER QR (SELESAI)
        // ======================
        $orderQuery = Order::where('order_status', 'selesai');

        if ($from && $to) {
            $orderQuery->whereBetween('created_at', [
                $from . ' 00:00:00',
                $to . ' 23:59:59'
            ]);
        }

        $orders = $orderQuery->get()->map(function ($item) {
            return [
                'kode'   => $item->invoice,
                'tanggal'=> $item->created_at,
                'total'  => $item->total,
                'sumber' => 'QR'
            ];
        });

        // ======================
        // GABUNGKAN
        // ======================
        $data = $transaksi
            ->merge($orders)
            ->sortByDesc('tanggal')
            ->values();

        return view('laporan.index', compact('data', 'from', 'to'));
    }

    /*
    |--------------------------------------------------------
    | EXPORT PDF
    |--------------------------------------------------------
    */
    public function pdf(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        $data = $this->getLaporanData($from, $to);

        $pdf = Pdf::loadView('laporan.pdf', compact('data', 'from', 'to'));

        return $pdf->stream('laporan-transaksi.pdf');
    }

    /*
    |--------------------------------------------------------
    | EXPORT EXCEL
    |--------------------------------------------------------
    */
    public function excel(Request $request)
    {
        $from = $request->from;
        $to   = $request->to;

        $data = $this->getLaporanData($from, $to);

        $filename = "laporan-transaksi.xls";

        $headers = [
            "Content-type" => "application/vnd-ms-excel",
            "Content-Disposition" => "attachment; filename=$filename"
        ];

        $content = "
        <h2>LAPORAN TRANSAKSI</h2>
        <p>Periode: " .
        ($from ? date('d-m-Y', strtotime($from)) : '-') .
        " s/d " .
        ($to ? date('d-m-Y', strtotime($to)) : '-') .
        "</p>

        <table border='1'>
            <tr>
                <th>No</th>
                <th>Kode</th>
                <th>Sumber</th>
                <th>Tanggal</th>
                <th>Jam</th>
                <th>Total</th>
            </tr>
        ";

        $grandTotal = 0;

        foreach ($data as $i => $d) {

            $tanggal = date('d-m-Y', strtotime($d['tanggal']));
            $jam     = date('H:i', strtotime($d['tanggal']));

            $content .= "
            <tr>
                <td>".($i+1)."</td>
                <td>{$d['kode']}</td>
                <td>{$d['sumber']}</td>
                <td>{$tanggal}</td>
                <td>{$jam}</td>
                <td>Rp ".number_format($d['total'])."</td>
            </tr>
            ";

            $grandTotal += $d['total'];
        }

        $content .= "
        <tr>
            <td colspan='5'><b>TOTAL</b></td>
            <td><b>Rp ".number_format($grandTotal)."</b></td>
        </tr>
        ";

        $content .= "</table>";

        return response($content, 200, $headers);
    }

    /*
    |--------------------------------------------------------
    | 🔥 HELPER GABUNG DATA
    |--------------------------------------------------------
    */
    private function getLaporanData($from, $to)
    {
        $transaksi = Transaksi::get()->map(function ($item) {
            return [
                'kode'   => $item->kode,
                'tanggal'=> $item->tanggal,
                'total'  => $item->total,
                'sumber' => 'Kasir'
            ];
        });

        $orders = Order::where('order_status', 'selesai')
            ->get()
            ->map(function ($item) {
                return [
                    'kode'   => $item->invoice,
                    'tanggal'=> $item->created_at,
                    'total'  => $item->total,
                    'sumber' => 'QR'
                ];
            });

        return $transaksi
            ->merge($orders)
            ->sortByDesc('tanggal')
            ->values();
    }
}