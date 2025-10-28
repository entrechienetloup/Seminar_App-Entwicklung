<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <title>@yield('title')</title>
    <!-- In der Testphase des Prototypen zum Anlegen von allen Testdaten und testen von Funktionen -->
</head>
<body>
    @yield('content')
</body>
</html>
@extends('layouts.app')

@section('title', 'Startseite')

@section('content')
<!--<h2>Neues Fahrzeug anlegen</h2>
<form method="POST" action="/fahrzeug-anlegen">
    @csrf
    <label for="fahrzeug_id">Name:</label>
    <input type="text" name="fahrzeug_id" required><br><br>

    <label for="type">Type:</label>
    <input type="text" name="type" required><br><br>

    <label for="status">Status:</label>
    <input type="text" name="status" required><br><br>

    <label for="zeitstempel">Zeitstempel:</label>
    <input type="text" name="zeitstempel"><br><br>

    <label for="ladestand">Ladestand:</label>
    <input type="text" name="ladestand"><br><br>

    <label for="akkuzustand">Akkuzustand:</label>
    <input type="text" name="akkuzustand"><br><br>

    <label for="akt_ta">TransportaufragID:</label>
    <input type="text" name="akt_ta"><br><br>

    <label for="x">X-Koordinate:</label>
    <input type="text" name="x" required><br><br>

    <label for="y">Y-Koordinate:</label>
    <input type="text" name="y" required><br><br>

    <button type="submit">Fahrzeug anlegen</button>
</form>

<hr>
<h2>Fahrzeug bearbeiten</h2>
@if(session('success'))
    <p style="color: green;">{{ session('success') }}</p>
@endif
<form method="POST" action="/fahrzeug-bearbeiten">
    @csrf
    <label for="fahrzeug_id">Fahrzeug auswählen:</label>
    <select name="fahrzeug_id" required>
        @foreach($fahrzeuge as $fz)
            <option value="{{ $fz->fahrzeug_id }}">
                Fahrzeug {{ $fz->fahrzeug_id }} (x={{ $fz->x }}, y={{ $fz->y }}, Meldung={{ $fz->meldung }})
            </option>
        @endforeach
    </select><br><br>

    <label for="x">Neue X-Koordinate:</label>
    <input type="number" name="x" required><br><br>

    <label for="y">Neue Y-Koordinate:</label>
    <input type="number" name="y" required><br><br>

    <label for="meldung">Meldung:</label>
    <input type="text" name="ämeldung" required><br><br>

    <button type="submit">Fahrzeug aktualisieren</button>
</form>

<hr>
<h2>Neuen Lagerplatz anlegen</h2>
<form method="POST" action="/lagerplatz-anlegen">
    @csrf

    <label for="lagerplatz_id">LagerplatzID:</label>
    <input type="text" name="lagerplatz_id" required><br><br>

    <label for="type">Typ:</label>
    <input type="text" name="type" required><br><br>

    <label for="x">X-Koordinate:</label>
    <input type="number" name="x" required><br><br>

    <label for="y">Y-Koordinate:</label>
    <input type="number" name="y" required><br><br>

    <label for="anzahl">Kapazität:</label>
    <input type="number" name="anzahl" required><br><br>

    <label for="belegt">Belegt:</label>
    <input type="number" name="belegt" required><br><br>

    <button type="submit">Lagerplatz anlegen</button>
</form>

<hr>
<h2>Lagerplatz bearbeiten</h2>
<form method="POST" action="/lagerplatz-bearbeiten">
    @csrf
    <label for="lagerplatz_id">Lagerplatz auswählen:</label>
    <select name="lagerplatz_id" required>
        @foreach($lagerplaetze as $lp)
            <option value="{{ $lp->lagerplatz_id }}">
                LP {{ $lp->lagerplatz_id }} ( Typ={{ $lp->type }}, {{ $lp->belegt }}/{{ $lp->anzahl }})
            </option>
        @endforeach
    </select><br><br>

    <label for="type">Neuer Typ:</label>
    <input type="text" name="type" required><br><br>

    <label for="anzahl">Kapazität:</label>
    <input type="number" name="anzahl" required><br><br>

    <label for="belegt">Belegt:</label>
    <input type="number" name="belegt" required><br><br>

    <button type="submit">Lagerplatz aktualisieren</button>
</form>

<hr>

<h2>Neuen Auftrag anlegen</h2>
<form method="POST" action="/auftrag-anlegen">
    @csrf
    <label for="transportauftrag_id">Name:</label>
    <input type="text" name="transportauftrag_id" required><br><br>

    <label for="status">Status:</label>
    <input type="text" name="status" required><br><br>

    <label for="startort_id">Startort:</label>
    <input type="text" name="startort_id"><br><br>

    <label for="zielort_id">Zielort:</label>
    <input type="text" name="zielort_id"><br><br>

    <label for="fahrzeug_id">FahrzeugID:</label>
    <input type="text" name="fahrzeug_id"><br><br>

    <button type="submit">Auftrag anlegen</button>
</form>

<hr>
<h2>Auftrag bearbeiten</h2>
<form method="POST" action="/auftrag-bearbeiten">
    @csrf
    <label for="transportauftrag_id">Lagerplatz auswählen:</label>
    <select name="transportauftrag_id" required>
        @foreach($auftraege as $at)
            <option value="{{ $at->transportauftrag_id }}">
                LP {{ $at->transportauftrag_id }} (Startort={{ $at->startort_id }}, Zielort={{ $at->zielort_id }}, Fahrzeug={{ $at->fahrzeug_ide }})
            </option>
        @endforeach
    </select><br><br>

    <label for="status">Status:</label>
    <input type="text" name="status" required><br><br>

    <label for="startort_id">Startort:</label>
    <input type="text" name="startort_id"><br><br>

    <label for="zielort_id">Zielort:</label>
    <input type="text" name="zielort_id"><br><br>

    <label for="fahrzeug_id">FahrzeugID:</label>
    <input type="text" name="fahrzeug_id"><br><br>


    <button type="submit">Lagerplatz aktualisieren</button>
</form>

<hr>
<h2>Neue Route anlegen</h2>
<form method="POST" action="{{ route('route.store') }}">
    @csrf

    Fahrzeug-Auswahl -->
    <!--<label for="fahrzeug_id">Fahrzeug auswählen:</label>
    <select name="fahrzeug_id" required>
        @foreach($fahrzeuge as $fz)
            <option value="{{ $fz->fahrzeug_id }}">Fahrzeug {{ $fz->fahrzeug_id }}</option>
        @endforeach
    </select><br><br>
    <br><br>
    Ziel-Lagerplatz --><!--
    <label for="startort_id">Startort:</label>
    <select name="startort_id" required>
        @foreach($lagerplaetze as $lp)
            <option value="{{ $lp->lagerplatz_id }}">Lagerplatz {{ $lp->lagerplatz_id }}</option>
        @endforeach
    </select><br><br>

    <label for="zielort_id">Zielortz:</label>
    <select name="zielort_id" required>
        @foreach($lagerplaetze as $lp)
            <option value="{{ $lp->lagerplatz_id }}">Lagerplatz {{ $lp->lagerplatz_id }}</option>
        @endforeach
    </select><br><br>
    <br><br>
    <button type="submit">Route speichern</button>

    <div style="display: flex; gap: 40px;">
         inke Liste: Alle Punkte --><!-- 
        <div>
            <h4>Alle Routenpunkte</h4>
            <ul id="alle-punkte" class="punkteliste">
                @foreach($routenpunkte as $punkt)
                    <li data-id="{{ $punkt->routenpunkte_id }}">
                        {{ $punkt->routenpunkte_id }}
                    </li>
                @endforeach
            </ul>
        </div>

        Rechte Liste: Ausgewählte Punkte --><!-- 
        <div>
            <h4>Ausgewählte Punkte (Reihenfolge)</h4>
            <ul id="ausgewaehlte-punkte" class="punkteliste"></ul>

            Hidden input für Reihenfolge --><!-- 
            <input type="hidden" name="routenpunkte" id="routenpunkte">
        </div>
    </div>
</form>-->
<br>
<!--Szenario Daten einfügen-->
<form action="{{ route('daten.seed') }}" method="GET">
    
    <button type="submit">Initiale Daten einfügen</button> 
</form>
<script>

<p><a href="/">Zurück zur Karte</a></p>
@endsection
<style>
.punkteliste {
    list-style: none;
    padding: 10px;
    width: 250px;
    min-height: 200px;
    border: 1px solid #ccc;
    background-color: #f8f8f8;
}
.punkteliste li {
    padding: 8px;
    margin-bottom: 5px;
    background-color: #e0e0e0;
    cursor: grab;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
//Für initale Test zum anlegen von Routen
<script>
document.addEventListener('DOMContentLoaded', function () {
  new Sortable(document.getElementById('alle-punkte'), {
    group: { name: 'punkte', pull: 'clone', put: false },
    sort: false
  });

  const rechteListe = document.getElementById('ausgewaehlte-punkte');
  new Sortable(rechteListe, {
    group: { name: 'punkte', pull: true, put: true },
    animation: 150,
    onSort: updateHiddenInput,
    onAdd: updateHiddenInput,
    onRemove: updateHiddenInput
  });

  function updateHiddenInput() {
    const selected = [];
    rechteListe.querySelectorAll('li').forEach(li => {
      selected.push(li.dataset.id);
    });
    document.getElementById('routenpunkte').value = selected.join(',');
  }
});
</script>