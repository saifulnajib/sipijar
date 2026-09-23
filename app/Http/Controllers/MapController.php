<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SpreadsheetPju;
use Inertia\Inertia;

class MapController extends Controller
{
    public function index()
    {
        // Membatasi 1500 titik agar peta tidak tertutup penuh (karena total ada 8778 data)
        $spreadsheetData = SpreadsheetPju::take(1500)->get();

        $idpels = $spreadsheetData->map(function ($item) {
            $status = strtoupper($item->status_meter) === 'METER' ? 'meterisasi' : 'abonemen';
            return [
                'idpel_number' => $item->idpel ?? '-',
                'name' => 'PJU ' . $item->no_urut . ($item->jenis_lampu ? ' - ' . $item->jenis_lampu : ''),
                'address' => $item->alamat,
                'lat' => $item->lat,
                'lng' => $item->lng,
                'status' => $status,
                'substations' => [
                    [
                        'current_kw' => number_format($item->besar_daya / 1000, 3)
                    ]
                ]
            ];
        });

        return Inertia::render('Maps/Index', [
            'idpels' => $idpels,
        ]);
    }
}
