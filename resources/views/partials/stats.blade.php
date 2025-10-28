@php
  $activeFahrzeuge = collect($fahrzeuge)->where('status', 'aktiv')->count();
  $totalFahrzeuge = count($fahrzeuge);

  $activeAuftraege = collect($auftraege)->where('status', 'aktiv')->count();
  $completedAuftraege = collect($auftraege)->where('status', 'completed')->count();
  $oldestOpen = collect($auftraege)
                  ->where('status', 'active')
                  ->sortBy('created_at')
                  ->first();
@endphp

<ul class="list-group list-group-flush" style="font-size: 0.925rem;">
  <li class="list-group-item d-flex justify-content-between align-items-center">
    Aktive Fahrzeuge:
    <span class="badge bg-primary rounded-pill">{{ $totalFahrzeuge }}</span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    Alle Fahrzeuge:
    <span class="badge bg-secondary rounded-pill">{{ $totalFahrzeuge }}</span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    Aktive Transportaufträge:
    <span class="badge bg-primary rounded-pill">{{ $activeAuftraege }}</span>
  </li>
  <li class="list-group-item d-flex justify-content-between align-items-center">
    Erledigte Transportaufträge:
    <span class="badge bg-success rounded-pill">{{ $completedAuftraege }}</span>
  </li>
  @if($oldestOpen)
  <li class="list-group-item">
    Ältester aktiver Auftrag:<br>
    <strong>{{ $oldestOpen['name'] }}</strong><br>
    <small class="text-muted">{{ \Carbon\Carbon::parse($oldestOpen['created_at'])->diffForHumans() }}</small>
  </li>
  @endif
</ul>
