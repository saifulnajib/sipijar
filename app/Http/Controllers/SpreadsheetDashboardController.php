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
        $apiUrl = 'https://script.google.com/macros/s/AKfycbxSNjBgT6BaDGQ9k5bEbal3DDpt5i8t5RfG0vyT-GhnHeaT1bb-hYUQ1I879doUGCPAAA/exec';

        // Cache the spreadsheet data for 15 minutes to prevent slow page loads
        $data = Cache::remember('spreadsheet_data', 15 * 60, function () use ($apiUrl) {
            $response = Http::get($apiUrl);
            
            if ($response->successful()) {
                return $response->json();
            }
            
            return null;
        });

        $items = $data['data'] ?? [];
        $totalItems = count($items);
        
        // Calculate KPIs
        $totalDaya = 0;
        $ledCount = 0;
        $ballasCount = 0;
        $meterCount = 0;
        $abonemenCount = 0;

        // Collect unique IDPELs
        $idpels = [];

        foreach ($items as $item) {
            $daya = is_numeric($item['BESAR DAYA']) ? (int) $item['BESAR DAYA'] : 0;
            $totalDaya += $daya;

            if (strtoupper($item['JENIS LAMPU']) === 'LED') {
                $ledCount++;
            } elseif (strtoupper($item['JENIS LAMPU']) === 'BALLAS') {
                $ballasCount++;
            }

            if (strtoupper($item['METER/TERSEBAR']) === 'METER') {
                $meterCount++;
            } else {
                $abonemenCount++;
            }

            if (!empty($item['IDPEL'])) {
                $idpels[$item['IDPEL']] = true;
            }
        }

        $totalIdpel = count($idpels);
        $totalKw = $totalDaya / 1000;

        // Search and filter logic
        $search = $request->input('search');
        $jenisLampu = $request->input('jenis_lampu');
        $statusMeter = $request->input('status_meter');

        if (!empty($search)) {
            $items = array_filter($items, function($item) use ($search) {
                return (
                    stripos($item['ALAMAT'] ?? '', $search) !== false ||
                    stripos($item['IDPEL'] ?? '', $search) !== false ||
                    stripos($item['JENIS LAMPU'] ?? '', $search) !== false ||
                    stripos($item['NO URUT'] ?? '', $search) !== false
                );
            });
            $items = array_values($items); // re-index
        }

        if (!empty($jenisLampu) && $jenisLampu !== 'all') {
            $items = array_filter($items, function($item) use ($jenisLampu) {
                return strtoupper($item['JENIS LAMPU'] ?? '') === strtoupper($jenisLampu);
            });
            $items = array_values($items);
        }

        if (!empty($statusMeter) && $statusMeter !== 'all') {
            $items = array_filter($items, function($item) use ($statusMeter) {
                return strtoupper($item['METER/TERSEBAR'] ?? '') === strtoupper($statusMeter);
            });
            $items = array_values($items);
        }

        // Manual Pagination
        $perPage = 15;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = array_slice($items, ($currentPage - 1) * $perPage, $perPage);
        
        $paginatedItems = new LengthAwarePaginator($currentItems, count($items), $perPage, $currentPage, [
            'path' => LengthAwarePaginator::resolveCurrentPath(),
            'query' => $request->query(),
        ]);

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
     * Clear cache manually
     */
    public function clearCache()
    {
        Cache::forget('spreadsheet_data');
        return redirect()->route('dashboard.spreadsheet')->with('message', 'Data spreadsheet telah diperbarui.');
    }
}
