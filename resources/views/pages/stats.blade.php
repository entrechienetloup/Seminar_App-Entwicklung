@extends('layouts.app')

@section('title', 'Stats')

@section('content')
<div class="container py-4">

    {{-- KPI CARDS --}}
    <div class="row g-4 mb-4">
        @php
    $stats = [
        [
            'title' => 'Aktive Fahrzeuge',
            'value' => $activeFahrzeuge,
            'sub' => "von $totalFahrzeuge gesamt",
            'color' => 'text-success',
            'bar' => null
        ],
        [
            'title' => 'Wartende Transportaufträge',
            'value' => $queuedAuftraege,
            'sub' => "$activeAuftraege aktive Transportaufträge",
            'color' => 'text-warning',
        ],
        [
            'title' => 'Neue Aufträge (heute)',
            'value' => $todayAuftraege,
            'sub' => "Ø $avgCreatedPerDay / Tag, basierend auf der letzten Woche",
            'color' => '',
            'bar' => null
        ],
        [
            'title' => 'Meldungen heute',
            'value' => $meldungenToday,
            'sub' => "Ø $avgMeldungenPerDay täglich (letzte Woche)",
            'color' => 'text-warning',
            'bar' => null
        ],
        [
            'title' => 'Ø Meldungshäufigkeit',
            'value' => $stoerungsrate ? "alle $stoerungsrate Std." : 'Nicht genug Daten',
            'sub' => "Basierend auf $totalMeldungen Meldungen",
            'color' => 'text-muted',
            'bar' => null
        ],
        [
    'title' => 'Ø Akkuzustand',
    'value' => "$avgAkku %",
    'sub' => $minAkkuFahrzeug 
        ? "Niedrigster: {$minAkkuFahrzeug->fahrzeug_id} ({$minAkku} %)" 
        : '-',
    'color' => is_numeric($avgAkku) && $avgAkku < 60 ? 'text-danger' : 'text-success',
]

    ];
@endphp



        @foreach($stats as $stat)
        <div class="col-12 col-md-6 col-xl-4">
            <div class="card shadow-sm h-100">
                <div class="card-body d-flex flex-column justify-content-between h-100">
                    <div>
                        <h6 class="text-muted mb-1">{{ $stat['title'] }}</h6>
                        <h2 class="fw-bold {{ $stat['color'] ?? '' }} mb-0">{{ $stat['value'] }}</h2>
                        @if(!empty($stat['sub']))
                            <small class="text-muted fst-italic">{{ $stat['sub'] }}</small>
                        @endif
                    </div>
                    @if(isset($stat['bar']))
                        <div class="progress mt-3" style="height:6px;">
                            <div class="progress-bar bg-success" style="width:{{ $stat['bar'] }}%"></div>
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Fahrzeug-Typen Statistik --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light">
        <strong>Fahrzeug-Typen</strong>
    </div>
    <div class="card-body">
        <ul class="list-group list-group-flush">
            @forelse($fahrzeugTypen as $typ)
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    {{ $typ->type }}
                    <span class="badge bg-primary rounded-pill">{{ $typ->total }}</span>
                </li>
            @empty
                <li class="list-group-item text-muted">Keine Fahrzeugdaten vorhanden.</li>
            @endforelse
        </ul>
    </div>
</div>


   {{-- MELDUNGEN --}}
<div class="card shadow-sm  mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong>Meldungen (30 Tage)</strong>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#meldungTable" aria-expanded="false" aria-controls="meldungTable">
            Anzeigen
        </button>
    </div>

    <div id="meldungTable" class="collapse">
        <div class="card-body p-0" id="meldung-content">
        @include('partials.meldungTable', ['meldungen' => $meldungen])

    </div>
</div>
</div>


{{-- FAHRZEUGE MIT VIELEN MELDUNGEN --}}
<div class="card shadow-sm mb-4">
    <div class="card-header bg-light d-flex justify-content-between align-items-center">
        <strong>Fahrzeuge mit häufigen Meldungen (30 Tage)</strong>
        <button class="btn btn-sm btn-outline-primary" data-bs-toggle="collapse" data-bs-target="#fahrzeugeMeldungTable" aria-expanded="false" aria-controls="fahrzeugeMeldungTable">
            Anzeigen
        </button>
    </div>

    <div id="fahrzeugeMeldungTable" class="collapse">
        <div class="card-body p-0">
            <div class="table-responsive " style="max-height: 300px; overflow-y: auto;">
                <table class="table table-sm table-hover mb-0">
                    <thead class="table-light sticky-top">
    <tr>
        <th style="width: 20%;">Fahrzeug</th>
        <th style="width: 10%;">Gesamt</th>
        @foreach($alleTypen as $typ)
            <th class="text-nowrap">{{ $typ }}</th>
        @endforeach
    </tr>
</thead>
<tbody>
    @forelse($fahrzeugeMitMeldungen as $fahrzeug)
        <tr>
            <td>{{ $fahrzeug->fahrzeug_id }}</td>
            <td>{{ $fahrzeug->meldungen_count }}</td>
            @foreach($alleTypen as $typ)
                <td>
                    {{ $fahrzeug->meldungstypen[$typ] ?? 0 }}
                </td>
            @endforeach
        </tr>
    @empty
        <tr>
            <td colspan="{{ 2 + $alleTypen->count() }}" class="text-center text-muted py-3">
                Keine Daten vorhanden.
            </td>
        </tr>
    @endforelse
</tbody>

                </table>
            </div>
        </div>
    </div>
</div>

</div>
@endsection


<script>    

document.addEventListener('DOMContentLoaded', function () {
    document.addEventListener('click', function (e) {
        if (e.target.closest('#meldung-pagination a')) {
            e.preventDefault();
            const url = e.target.closest('a').href;

            fetch(url, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newContent = doc.querySelector('#meldung-content');
                document.querySelector('#meldung-content').innerHTML = newContent.innerHTML;
            })
            .catch(err => console.error(err));
        }
    });
});

document.addEventListener('DOMContentLoaded', function () {

    const toggleButtons = document.querySelectorAll('[data-bs-toggle="collapse"]');

    toggleButtons.forEach(function (btn) {
        const targetId = btn.getAttribute('data-bs-target');
        const collapseEl = document.querySelector(targetId);

        if (collapseEl) {
            collapseEl.addEventListener('shown.bs.collapse', function () {
                btn.textContent = 'Verbergen';
            });

            collapseEl.addEventListener('hidden.bs.collapse', function () {
                btn.textContent = 'Anzeigen';
            });
        }
    });
});

</script>