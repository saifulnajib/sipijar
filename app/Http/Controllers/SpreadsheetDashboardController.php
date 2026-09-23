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

        if (!empty($search)) {
            $query->where(function($q) use ($search) {
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

        $paginatedItems = $query->paginate(15)->withQueryString();

        $paginatedItems->getCollection()->transform(function ($item) {
            return [
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
        
        $totalDaya = \App\Models\SpreadsheetPju::sum('besar_daya');
        $ledCount = \App\Models\SpreadsheetPju::where('jenis_lampu', 'LED')->count();
        $ballasCount = \App\Models\SpreadsheetPju::where('jenis_lampu', 'BALLAS')->count();
        $meterCount = \App\Models\SpreadsheetPju::where('status_meter', 'METER')->count();
        $abonemenCount = \App\Models\SpreadsheetPju::where('status_meter', '!=', 'METER')->orWhereNull('status_meter')->count();

        $totalKw = $totalDaya / 1000;

        return \Inertia\Inertia::render('DashboardSpreadsheet', [
            'pjuData' => $paginatedItems,
            'kpis' => [
                'total_titik' => $totalItems,
                'total_idpel' => $totalIdpel,
                'total_daya_kw' => number_format($totalKw, 2, ',', '.'),
                'led_count' => $ledCount,
                'ballas_count' => $ballasCount,
                'meter_count' => $meterCount,
                'abonemen_count' => $abonemenCount,
            ],
            'filters' => $request->only(['search', 'jenis_lampu', 'status_meter'])
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
                if (!empty($item['KOORDINAT'])) {
                    $parts = explode(',', $item['KOORDINAT']);
                    if (count($parts) >= 2) {
                        $lat = trim($parts[0]);
                        $lng = trim($parts[1]);
                    }
                }

                $upsertData[] = [
                    'no_urut' => $item['NO URUT'],
                    'idpel' => $item['IDPEL'] ?? null,
                    'alamat' => $item['ALAMAT'] ?? null,
                    'jenis_lampu' => strtoupper($item['JENIS LAMPU'] ?? ''),
                    'besar_daya' => is_numeric($item['BESAR DAYA'] ?? null) ? (int) $item['BESAR DAYA'] : 0,
                    'status_meter' => strtoupper($item['METER/TERSEBAR'] ?? ''),
                    'lat' => $lat,
                    'lng' => $lng,
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
                        ['idpel', 'alamat', 'jenis_lampu', 'besar_daya', 'status_meter', 'lat', 'lng', 'updated_at']
                    );
                }
            }
        }

        return redirect()->route('dashboard.spreadsheet')->with('message', 'Data spreadsheet telah disinkronisasi ke database.');
    }
}
