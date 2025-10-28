<?php

namespace App\Imports;

use App\Models\Auftraege;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AuftraegeImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        return new Auftraege([
            'transportauftrag_id'   => $row['transportauftrag_id'],
            'status'                => $row['status'],
            'prioritaet'           => $row['prioritaet'],
            'startort_id'               => $row['startort_id'],
            'zielort_id'                => $row['zielort_id'],
            'fahrzeug_id'            => $row['fahrzeug_id'],
        ]);
    }
}
