<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Pagination\LengthAwarePaginator;

class SpreadsheetDashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\SpreadsheetPju::query();

        // Search and filter logic
        $search = $request->input('search');
        $jenisLampu = $request->input('jenis_lampu');
        $statusMeter = $request->input('status_meter');
        $survei = $request->input('survei');
        $meterisasi = $request->input('meterisasi');

        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('alamat', 'like', "%{$search}%")
                    ->orWhere('idpel', 'like', "%{$search}%")
                    ->orWhere('jenis_lampu', 'like', "%{$search}%")
                    ->orWhere('no_urut', 'like', "%{$search}%");
            });
        }

        if (!empty($jenisLampu) && $jenisLampu !== 'all') {
            $query->where('jenis_lampu', $jenisLampu);
        }

        if (!empty($statusMeter) && $statusMeter !== 'all') {
            $query->where('status_meter', $statusMeter);
        }

        if (!empty($survei) && $survei !== 'all') {
            if ($survei == 'BELUM') {
                $query->where('survei', $survei);
                $query->orWhere('survei', '');
            } else {
                $query->where('survei', $survei);
            }
        }

        if (!empty($meterisasi) && $meterisasi !== 'all') {
            $query->where('meterisasi', $meterisasi);
        }

        $paginatedItems = $query->paginate(15)->withQueryString();

        $paginatedItems->getCollection()->transform(function ($item) {
            return [
                'ID' => $item->id,
                'NO URUT' => $item->no_urut,
                'IDPEL' => $item->idpel,
                'ALAMAT' => $item->alamat,
                'JENIS LAMPU' => $item->jenis_lampu,
                'BESAR DAYA' => $item->besar_daya,
                'METER/TERSEBAR' => $item->status_meter,
                'LATITUDE' => $item->lat,
                'LONGITUDE' => $item->lng,
            ];
        });

        $totalItems = \App\Models\SpreadsheetPju::count();
        $totalIdpel = \App\Models\SpreadsheetPju::whereNotNull('idpel')->where('idpel', '!=', '')->distinct('idpel')->count('idpel');

        $idpelMeterCount = \App\Models\SpreadsheetPju::where('status_meter', 'METER')->distinct('idpel')->count('idpel');
        $idpelAbonemenCount = \App\Models\SpreadsheetPju::where(function ($q) {
            $q->where('status_meter', '!=', 'METER')->orWhereNull('status_meter');
        })->distinct('idpel')->count('idpel');

        $totalDaya = \App\Models\SpreadsheetPju::sum('besar_daya');
        $ledCount = \App\Models\SpreadsheetPju::where('jenis_lampu', 'LED')->count();
        $ballasCount = \App\Models\SpreadsheetPju::where('jenis_lampu', 'BALLAS')->count();
        $meterCount = \App\Models\SpreadsheetPju::where('status_meter', 'METER')->count();
        $abonemenCount = \App\Models\SpreadsheetPju::where('status_meter', '!=', 'METER')->orWhereNull('status_meter')->count();

        $surveiSudahCount = \App\Models\SpreadsheetPju::where(function ($q) {
            $q->where('status_meter', '!=', 'METER')->orWhereNull('status_meter');
        })->where('survei', 'SUDAH')->count();

        $surveiBelumCount = \App\Models\SpreadsheetPju::where(function ($q) {
            $q->where('status_meter', '!=', 'METER')->orWhereNull('status_meter');
        })->where(function ($q) {
            $q->where('survei', '!=', 'SUDAH')->orWhereNull('survei');
        })->count();

        $surveiPerUnit = \App\Models\SpreadsheetPju::where(function ($q) {
            $q->where('status_meter', '!=', 'METER')->orWhereNull('status_meter');
        })
            ->whereNotNull('unit_pln')
            ->where('unit_pln', '!=', '')
            ->selectRaw("
                unit_pln, 
                SUM(CASE WHEN survei = 'SUDAH' THEN 1 ELSE 0 END) as sudah_count,
                COUNT(*) as total_count
            ")
            ->groupBy('unit_pln')
            ->get()
            ->mapWithKeys(function ($item) {
                return [
                    $item->unit_pln => [
                        'count' => (int) $item->sudah_count,
                        'total' => (int) $item->total_count,
                        'percentage' => $item->total_count > 0 ? round(($item->sudah_count / $item->total_count) * 100) : 0,
                    ]
                ];
            })
            ->toArray();

        $statsPerUnit = \App\Models\SpreadsheetPju::whereNotNull('unit_pln')
            ->where('unit_pln', '!=', '')
            ->selectRaw("
                unit_pln,
                COUNT(*) as total_titik,
                SUM(CASE WHEN status_meter = 'METER' THEN 1 ELSE 0 END) as meter_count,
                SUM(CASE WHEN status_meter != 'METER' OR status_meter IS NULL THEN 1 ELSE 0 END) as abonemen_count
            ")
            ->groupBy('unit_pln')
            ->get()
            ->keyBy('unit_pln')
            ->toArray();

        $totalKw = $totalDaya / 1000;

        return \Inertia\Inertia::render('DashboardSpreadsheet', [
            'pjuData' => $paginatedItems,
            'kpis' => [
                'total_titik' => $totalItems,
                'total_idpel' => $totalIdpel,
                'idpel_meter_count' => $idpelMeterCount,
                'idpel_abonemen_count' => $idpelAbonemenCount,
                'total_daya_kw' => number_format($totalKw, 2, ',', '.'),
                'led_count' => $ledCount,
                'ballas_count' => $ballasCount,
                'meter_count' => $meterCount,
                'abonemen_count' => $abonemenCount,
                'survei_sudah_count' => $surveiSudahCount,
                'survei_belum_count' => $surveiBelumCount,
                'survei_per_unit' => $surveiPerUnit,
                'stats_per_unit' => $statsPerUnit,
            ],
            'filters' => $request->only(['search', 'jenis_lampu', 'status_meter', 'survei', 'meterisasi'])
        ]);
    }

    /**
     * Clear cache manually and sync data
     */
    public function clearCache()
    {
        set_time_limit(0); // Prevent maximum execution time error

        $apiUrl = 'https://script.google.com/macros/s/AKfycbxSNjBgT6BaDGQ9k5bEbal3DDpt5i8t5RfG0vyT-GhnHeaT1bb-hYUQ1I879doUGCPAAA/exec';

        $response = Http::get($apiUrl);
        if ($response->successful()) {
            $data = $response->json();
            $items = $data['data'] ?? [];

            $upsertData = [];
            $now = now();

            foreach ($items as $item) {
                if (empty($item['NO URUT'])) {
                    continue;
                }

                $lat = null;
                $lng = null;
                if (!empty($item['LOKASI'])) {
                    $parts = explode(',', $item['LOKASI']);
                    if (count($parts) >= 2) {
                        $lat = trim($parts[0]);
                        $lng = trim($parts[1]);
                    }
                }

                $upsertData[] = [
                    'no_urut' => $item['NO URUT'],
                    'alamat' => $item['ALAMAT'] ?? null,
                    'status_meter' => strtoupper($item['METER/TERSEBAR'] ?? ''),
                    'bentuk' => $item['BENTUK'] ?? null,
                    'idpel' => $item['IDPEL'] ?? null,
                    'tiang' => $item['TIANG'] ?? null,
                    'jenis_lampu' => strtoupper($item['JENIS LAMPU'] ?? ''),
                    'besar_daya' => is_numeric($item['BESAR DAYA'] ?? null) ? (int) $item['BESAR DAYA'] : 0,
                    'lat' => $lat,
                    'lng' => $lng,
                    'survei' => strtoupper($item['SURVEI'] ?? ''),
                    'meterisasi' => strtoupper($item['METERISASI'] ?? ''),
                    'tindak_lanjut' => $item['TINDAKLANJUT'] ?? null,
                    'kebutuhan_material' => $item['KEBUTUHAN MATERIAL'] ?? null,
                    'status_lampu' => $item['STATUS LAMPU'] ?? null,
                    'unit_pln' => $item['UNIT PLN'] ?? null,
                    'created_at' => $now,
                    'updated_at' => $now,
                ];
            }

            if (!empty($upsertData)) {
                // Bulk upsert chunks to avoid query length limits
                foreach (array_chunk($upsertData, 500) as $chunk) {
                    \App\Models\SpreadsheetPju::upsert(
                        $chunk,
                        ['no_urut'],
                        [
                            'alamat',
                            'status_meter',
                            'bentuk',
                            'idpel',
                            'tiang',
                            'jenis_lampu',
                            'besar_daya',
                            'lat',
                            'lng',
                            'survei',
                            'meterisasi',
                            'tindak_lanjut',
                            'kebutuhan_material',
                            'status_lampu',
                            'unit_pln',
                            'updated_at'
                        ]
                    );
                }
            }
        }

        return redirect()->route('dashboard')->with('message', 'Data spreadsheet telah disinkronisasi ke database.');
    }

    public function show($id)
    {
        $pju = \App\Models\SpreadsheetPju::findOrFail($id);
        return \Inertia\Inertia::render('SpreadsheetDetail', [
            'pju' => $pju
        ]);
    }
}
