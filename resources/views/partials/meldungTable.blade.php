
<div id="meldung-content">
    <div class="table-responsive table-striped table-light" style="max-height: 300px; overflow-y: auto;">
        <table class="table table-sm table-hover mb-0">
            <thead class="table-light sticky-top">
                <tr>
                    <th style="width: 10%; ">ID</th>
                    <th style="width: 20%; color:green; font-weight: bold;">Fahrzeug</th>
                    <th style="width: 50%;">Beschreibung</th>
                    <th style="width: 20%;">Datum</th>
                </tr>
            </thead>
            <tbody>
                @if ($meldungen->count())
    @foreach ($meldungen as $meldung)
        <tr>
            <td>{{ $meldung->id }}</td>
            <td style="color:green; font-weight: bold;">{{ $meldung->fahrzeug_id }}</td>
            <td>{{ $meldung->beschreibung }}</td>
            <td>
                <small class="text-muted">
                    {{ \Carbon\Carbon::parse($meldung->gemeldet_am)->format('d.m.Y H:i') }}
                </small>
            </td>
        </tr>
    @endforeach
@else
    <tr>
        <td colspan="4" class="text-center text-muted py-3">Keine Meldungen vorhanden.</td>
    </tr>
@endif

            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div id="meldung-pagination" class="px-3 py-2 border-top">
        {{ $meldungen->links('pagination::bootstrap-5') }}
    </div>
</div>
