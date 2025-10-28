<?php

namespace App\Http\Controllers;
use App\Models\Lagerplatz;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LagerplatzController extends Controller
{
    public function index(): JsonResponse //Lagerplätze werden geladen
    {
        return response()->json(Lagerplatz::all());
    }
    public function updatePosition(Request $request, $lagerplatz_id) //Lagerplätze werden geupdatet
    {
    $request->validate([
            'x' => 'required|integer',
            'y' => 'required|integer',
        ]);

        $platz = Lagerplatz::findOrFail($lagerplatz_id);
        $platz->update([
            'x' => $request->input('x'),
            'y' => $request->input('y'),
        ]);

        return response()->json(['success' => true]);
}

}

