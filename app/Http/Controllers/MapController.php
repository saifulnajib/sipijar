<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MapController extends Controller
{
    public function index()
    {
        $idpels = \App\Models\Idpel::with('substations')->get();

        return \Inertia\Inertia::render('Maps/Index', [
            'idpels' => $idpels,
        ]);
    }
}
