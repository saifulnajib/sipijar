<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IdpelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = \App\Models\SpreadsheetPju::query()
            ->selectRaw("
                idpel,
                MAX(alamat) as alamat,
                MAX(unit_pln) as unit_pln,
                MAX(status_meter) as status_meter,
                COUNT(*) as total_titik,
                SUM(besar_daya) as total_daya,
                MAX(survei) as survei,
                MAX(meterisasi) as meterisasi,
                MAX(lat) as lat,
                MAX(lng) as lng
            ")
            ->whereNotNull('idpel')
            ->where('idpel', '!=', '');

        // Filter status_meter: meter atau non-meter
        $status = $request->input('status', 'all');
        if ($status === 'meter') {
            $query->where('status_meter', 'METER');
        } elseif ($status === 'non-meter' || $status === 'non_meter') {
            $query->where(function ($q) {
                $q->where('status_meter', '!=', 'METER')->orWhereNull('status_meter');
            });
        }

        // Filter unit PLN
        $unitPln = $request->input('unit_pln', 'all');
        if (!empty($unitPln) && $unitPln !== 'all') {
            $query->where('unit_pln', $unitPln);
        }

        // Search IDPEL, Alamat, atau Unit PLN
        $search = $request->input('search');
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('idpel', 'like', "%{$search}%")
                  ->orWhere('alamat', 'like', "%{$search}%")
                  ->orWhere('unit_pln', 'like', "%{$search}%");
            });
        }

        $query->groupBy('idpel')->orderBy('idpel', 'asc');

        $idpels = $query->paginate(15)->withQueryString();

        // Statistik KPI untuk IDPEL
        $totalIdpelCount = \App\Models\SpreadsheetPju::whereNotNull('idpel')
            ->where('idpel', '!=', '')
            ->count(\Illuminate\Support\Facades\DB::raw('DISTINCT idpel'));

        $meterCount = \App\Models\SpreadsheetPju::whereNotNull('idpel')
            ->where('idpel', '!=', '')
            ->where('status_meter', 'METER')
            ->count(\Illuminate\Support\Facades\DB::raw('DISTINCT idpel'));

        $nonMeterCount = \App\Models\SpreadsheetPju::whereNotNull('idpel')
            ->where('idpel', '!=', '')
            ->where(function ($q) {
                $q->where('status_meter', '!=', 'METER')->orWhereNull('status_meter');
            })
            ->count(\Illuminate\Support\Facades\DB::raw('DISTINCT idpel'));

        $units = \App\Models\SpreadsheetPju::whereNotNull('unit_pln')
            ->where('unit_pln', '!=', '')
            ->distinct()
            ->pluck('unit_pln');

        return \Inertia\Inertia::render('Idpels/Index', [
            'idpels' => $idpels,
            'units' => $units,
            'kpis' => [
                'total' => $totalIdpelCount,
                'meter' => $meterCount,
                'non_meter' => $nonMeterCount,
            ],
            'filters' => [
                'search' => $search ?? '',
                'status' => $status,
                'unit_pln' => $unitPln,
            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts = \App\Models\District::all();
        return \Inertia\Inertia::render('Idpels/Create', [
            'districts' => $districts
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'idpel_number' => 'required|string|max:20|unique:idpels,idpel_number',
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:meterisasi,abonemen',
            'district_id' => 'nullable|exists:districts,id',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        \App\Models\Idpel::create($validated);

        return redirect()->route('idpels.index')->with('success', 'Data IDPEL berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $idpel = \App\Models\Idpel::with(['district', 'substations'])->findOrFail($id);
        return \Inertia\Inertia::render('Idpels/Show', [
            'idpel' => $idpel
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $idpel = \App\Models\Idpel::findOrFail($id);
        $districts = \App\Models\District::all();
        
        return \Inertia\Inertia::render('Idpels/Edit', [
            'idpel' => $idpel,
            'districts' => $districts
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $idpel = \App\Models\Idpel::findOrFail($id);

        $validated = $request->validate([
            'idpel_number' => 'required|string|max:20|unique:idpels,idpel_number,' . $idpel->id,
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
            'status' => 'required|in:meterisasi,abonemen',
            'district_id' => 'nullable|exists:districts,id',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $idpel->update($validated);

        return redirect()->route('idpels.index')->with('success', 'Data IDPEL berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $idpel = \App\Models\Idpel::findOrFail($id);
        $idpel->delete();

        return redirect()->route('idpels.index')->with('success', 'Data IDPEL berhasil dihapus.');
    }
}
