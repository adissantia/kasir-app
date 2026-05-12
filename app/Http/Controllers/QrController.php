<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class QrController extends Controller
{
    public function index($meja)
    {
        // simpan meja ke session
        session(['table_number' => $meja]);

        // lempar ke menu customer
        return redirect('/table/' . $meja);
    }
}