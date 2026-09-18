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
        $query = \App\Models\Idpel::with(['district', 'substations']);

        if ($request->has('search') && $request->search != '') {
            $query->where('idpel_number', 'like', '%' . $request->search . '%')
                  ->orWhere('name', 'like', '%' . $request->search . '%');
        }

        if ($request->has('district') && $request->district != 'all') {
            $query->whereHas('district', function ($q) use ($request) {
                $q->where('id', $request->district); // assuming district dropdown sends district ID
            });
        }

        $idpels = $query->paginate(10)->withQueryString();
        $districts = \App\Models\District::all();

        return \Inertia\Inertia::render('Idpels/Index', [
            'idpels' => $idpels,
            'districts' => $districts,
            'filters' => $request->only(['search', 'district']),
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
