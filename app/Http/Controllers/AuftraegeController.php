<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Auftraege;
use Illuminate\Support\Facades\DB;
class AuftraegeController extends Controller
{
    public function index()
    {
        $alle = Auftraege::orderByRaw(
                "CASE status
                    WHEN 'wird ausgeführt' THEN 1
                    WHEN 'wartet'           THEN 2
                    WHEN 'beendet'          THEN 3
                    ELSE 4
                 END"
            )
            ->orderBy('prioritaet')
            ->get();

        return response()->json($alle);
    }
    public function priorisieren($id)//nach drei Statuswerten sortieren
    // 'wird ausgeführt' steht immer vor 'wartet', 'beendet' egal wie niedrig die Priorität ist
    {
        DB::transaction(function() use ($id) {
            $auftrag  = Auftraege::findOrFail($id);
            $laufende = Auftraege::where('status', 'wird ausgeführt')
                        ->orderBy('prioritaet')->get();
            $wartende = Auftraege::where('status', 'wartet')
                        ->orderBy('prioritaet')->get();

            if ($auftrag->status === 'wird ausgeführt') {
                // laufende: angeklickten zuerst, dann alle anderen
                $neue = collect([$auftrag])
                    ->merge($laufende->where('transportauftrag_id', '!=', $id));

                $prio = 1;
                foreach ($neue as $a) {
                    $a->update(['prioritaet' => $prio++]);
                }
            }
            elseif ($auftrag->status === 'wartet') {
                // warten: angeklickten zuerst, dann Rest; Priorität ab Anzahl laufender + 1
                $offset = $laufende->count() + 1;
                $neue   = collect([$auftrag])
                    ->merge($wartende->where('transportauftrag_id', '!=', $id));

                $prio = $offset;
                foreach ($neue as $a) {
                    $a->update(['prioritaet' => $prio++]);
                }
            }
            // beendet: bleibt unverändert hinten
        });

        return response()->json(['ok' => true]);
    }
    public function updateJSON(Request $request, $id) {
        $auftrag = Auftraege::findOrFail($id);
        $data = $request->only([
            'startort', 'zielort', 'fahrzeug_id'
        ]);
        $auftrag->fill($data);
        $auftrag->save();
        return response()->json(['success' => true, 'data' => $auftrag]);
    }

    public function deleteJSON($id) {
        $auftrag = Auftraege::findOrFail($id);
        $auftrag->delete();
        return response()->json(['success' => true]);
    }

}
    