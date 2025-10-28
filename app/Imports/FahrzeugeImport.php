<?php

namespace App\Imports;

use App\Models\Fahrzeuge;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class FahrzeugeImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Fahrzeuge([
            'fahrzeug_id'         => $row['fahrzeug_id'],
            'status'       => $row['status'],
            'zeitstempel'  => $row['zeitstempel'],
            'ladestand'         => $row['ladestand'],
            'akkuzustand'       => $row['akkuzustand'],
            'akt_ta'            => $row['akt_ta'],
            'meldung'           => $row['meldung'],
            'x' => $row['x'],
            'y' => $row['y']
        ]);
    }
}
