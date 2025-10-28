<head>
  <meta name="csrf-token" content="{{ csrf_token() }}">
<style>
  /* The red "!" badge */
.warn-badge {
    position: absolute;
     top: -2px;
    right: -2px;
    background: red;
    color: white;
    font-weight: bold;
    font-size: 11px;
    width: 16px;
    height: 16px;
    line-height: 14px;
    text-align: center;
    border-radius: 50%;
    box-shadow: 0 0 2px rgba(0, 0, 0, 0.5);
}
  .red-dot {
    background-color: red;
}
        .leaflet-tooltip.meldung {
            background-color: red !important;
            color: white;
            border: 1px solid darkred;
            font-weight: bold;
        }
        .marker-label {
 font-size: 10px;
    margin-top: -5px;
    line-height: 0.5;
    color: white;
    white-space: nowrap;
    font-weight: bold;
    background: black;
    padding: 2px 4px;
  border-radius: 3px;
  box-shadow: 0 0 3px rgba(0,0,0,0.2);
}

        .marker-wrapper {
    position: relative;
    text-align: center;
    display: inline-block;
}
.leaflet-tooltip.meldung {
  background-color: red !important;
  color: white;
  border: 1px solid darkred;
  font-weight: bold;
}
</style>
</head>
<div class="sidebar vh-100 p-3 sticky-top d-flex d-none d-lg-flex flex-column flex-shrink-0 p-3 bg-light border-right shadow" style="width: 250px;">
    <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-dark text-decoration-none">
      <img class="bi me-2" width="40" height="32"use src="images/logo.png">
      <span class="fs-6">Fahrzeugüberwachungs&shy;system</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
      <li>
          <a href="{{ route('dashboard') }}"
             class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('dashboard') ? 'active' : 'link-dark' }}">
              Dashboard
              <i class="bi bi-speedometer2"></i>
          </a>
      </li>

      <li>
          <a href="{{ route('fahrzeuge') }}"
             class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('fahrzeuge') ? 'active' : 'link-dark' }}">
             Übersicht Fahrzeuge
              <i class="bi bi-truck"></i>
          </a>
      </li>

      <li>
          <a href="{{ route('auftraege') }}"
             class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('auftraege') ? 'active' : 'link-dark' }}">
             Übersicht Transportaufträge
              <i class="bi bi-list-task"></i>
          </a>
          <script>
            //Buttons zum Szenario starten
            function szenarioStarten() {
              console.log("Szenario gestartet!");
            }
          </script>
              <button onclick="szenarioStarten()">Szenario starten</button>
              <button onclick="szenarioFortsetzen()">Szenario fortsetzen</button>
          <br>
      </li>
    </ul>
    <div class="mt-auto nav nav-pills flex-column">
    <hr>
    <a href="{{ route('profile.show') }}"
       class="nav-link d-flex justify-content-between align-items-center {{ request()->routeIs('profile.show') ? 'active' : 'link-dark' }}"
       id="nav-profil">
       <strong>{{ auth()->user()->name }}</strong>
        <i class="bi bi-person-circle"></i>
        
    </a>
    @include('partials.role-switch')
  </div>
    </div>



<nav class="nav nav-underline navbar-light fixed-bottom vw-100 d-lg-none mobile-nav">
  <div class="container-fluid d-flex justify-content-around ">
    <a href="{{ route('dashboard') }}"
   class="nav-link text-center {{ request()->routeIs('dashboard') ? 'active' : '' }}">
    <div style="font-size: 1em;"><i class="bi bi-speedometer2"></i></div>
    <small class="d-block" style="font-size: 0.7rem;">Dashboard</small>
</a>

      <a href="{{ route('fahrzeuge') }}"
         class="nav-link text-center {{ request()->routeIs('fahrzeuge') ? 'active' : '' }}">
         <div style="font-size: 1rem;"><i class="bi bi-truck"></i></div>
          <small class="d-block" style="font-size: 0.7rem;">Übersicht Fahrzeuge</small>
      </a>

      <a href="{{ route('auftraege') }}"
         class="nav-link text-center {{ request()->routeIs('auftraege') ? 'active' : '' }}">
         <div style="font-size: 1rem;"><i class="bi bi-list-task"></i></div>
          <small class="d-block" style="font-size: 0.7rem;">Übersicht Transportaufträge</small>
      </a>

      @auth
      <a href="{{ route('profile.show') }}"
         class="nav-link text-center {{ request()->routeIs('profile.show') ? 'active' : '' }}">
         <div style="font-size: 1rem;"><i class="bi bi-person-circle"></i></div>
          <small class="d-block" style="font-size: 0.7rem;"><strong>{{ auth()->user()->name }}</strong></small>
      </a>
      @endauth
  </div>
</nav>
<script>
const tolerance = 0.01; // Toleranz
const ladeIntervallMs = 5000; // alle 0.5 Sekunden
const ladeAbnahmeProIntervall = 1; // 2% pro Intervall
const ladezustandMap = {};
const fahrzeugStopFlags = {
  HS06: false
};
function positionsSindGleich(pos1, pos2) { //Soll HS06 und HS03 stoppen, wenn HS03 bestimme Koordinate erreichz
  return Math.abs(pos1.x - pos2.x) < tolerance && Math.abs(pos1.y - pos2.y) < tolerance;
}
function bewegeFahrzeugEntlangRoute(fahrzeugId, route, startIndex = 0) { //damit sich Farhzeuge bewegen
  if (!route || route.length < startIndex + 1) return; //Fehlerbehebung

  const marker = markerMap[fahrzeugId];//Fehlerbehebung
  if (!marker) return;
  //Daten zum starten
  let index = startIndex + 1;
  let currentPos = { ...route[startIndex] };
  let targetPos = { ...route[index] };
  const speed = 1;
  const tolerance = 0.5;
  let gestoppt = false; //zum stoppen HS03 und HS06

  function step() { 
    if (fahrzeugId === 'HS06' && fahrzeugStopFlags.HS06) return;
    if (gestoppt) return;

    // Bewegung der Fahrzeuge, immer nur Änderung x oder y
    if (Math.abs(currentPos.x - targetPos.x) > tolerance) {
      const dir = targetPos.x > currentPos.x ? 1 : -1;
      currentPos.x += dir * speed;
      if ((dir > 0 && currentPos.x > targetPos.x) || (dir < 0 && currentPos.x < targetPos.x)) {
        currentPos.x = targetPos.x;
      }
    } else if (Math.abs(currentPos.y - targetPos.y) > tolerance) {
      const dir = targetPos.y > currentPos.y ? 1 : -1;
      currentPos.y += dir * speed;
      if ((dir > 0 && currentPos.y > targetPos.y) || (dir < 0 && currentPos.y < targetPos.y)) {
        currentPos.y = targetPos.y;
      }
    }

    marker.setLatLng([currentPos.y, currentPos.x]);

    if ( //wenn SH03 bestimme Postion erreichz
      fahrzeugId === 'HS03' &&
      Math.abs(currentPos.x - 319) < tolerance &&
      Math.abs(currentPos.y - 371) < tolerance
    ) {
      gestoppt = true;

      fetch('/fahrzeug/status', { //Fehlermeldung wird ausgegeben für HS03
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ fahrzeug_id: 'HS03', meldung: 'Nicht Erreichbar', status: 'nicht erreichbar' })
      })
      .then(() => aktualisiereMarkerStatus('HS03', 'Nicht Erreichbar', 'nicht erreichbar'));

      fahrzeugStopFlags.HS06 = true; // HS06 gestoppz

      fetch('/fahrzeug/status', { //Fehlermeldung wird ausgegeben für HS06
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ fahrzeug_id: 'HS06', meldung: 'Route Blockiert', status: 'störung' })
      })
      .then(() => aktualisiereMarkerStatus('HS06', 'Route Blockiert', 'störung'));

      return;
    }

    if (positionsSindGleich(currentPos, targetPos)) { //Fahrzeugpositionen werden gespeichert
      index++;
      if (index >= route.length) {
        // Am Ende der Route: Position in DB speichern
        fetch('/fahrzeug/update-position', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
          },
          body: JSON.stringify({
            fahrzeug_id: fahrzeugId,
            x: currentPos.x,
            y: currentPos.y
          })
        }).then(res => {
          if (!res.ok) {
            console.error(`Fehler beim Speichern der Position von ${fahrzeugId}`);
          }
        });

        return;
      }
      targetPos = { ...route[index] };
    }

    requestAnimationFrame(step);
  }

  requestAnimationFrame(step);
}
function aktualisiereMarkerStatus(fahrzeugId, meldung, status) { //Merker für Fehlermeldung während Szenario 
  const marker = markerMap[fahrzeugId];
  if (!marker) return;

  const meldungText = meldung?.trim().toLowerCase() || '';
  const hasMeldung = meldungText !== 'keine' && meldungText !== '';
  const isBatteryIssue = meldungText.includes('batterie') || meldungText.includes('akku');

  const meldungIcon = isBatteryIssue
    ? '<i class="bi bi-battery"></i>'
    : '!';

  const iconElement = marker.getElement();
  if (!iconElement) return;

  const wrapper = iconElement.querySelector('.marker-wrapper');
  if (wrapper) {
    const existingBadge = wrapper.querySelector('.warn-badge');
    if (hasMeldung) {
      if (existingBadge) {
        existingBadge.innerHTML = meldungIcon;
      } else {
        const badge = document.createElement('div');
        badge.className = 'warn-badge';
        badge.innerHTML = meldungIcon;
        wrapper.appendChild(badge);
      }
    } else if (existingBadge) {
      existingBadge.remove();
    }

    //Label Rot wenn Meldung
    const label = wrapper.querySelector('.marker-label');
    if (label) {
      label.classList.toggle('meldung-label', hasMeldung);
    }
  }

  // Tooltip wird neu gesetzt, damit alles geladen wird
  const tooltipText = hasMeldung ? meldung : `Status: ${status}`;
  const offset = [-15, -45];

  marker.unbindTooltip();
  marker.bindTooltip(tooltipText, {
    offset: offset,
    permanent: false,
    direction: 'center',
    className: hasMeldung ? 'meldung' : ''
  });
}
function szenarioStarten() { //Funktion hinter szenario Starten Button
  console.log("Szenario starten aufgerufen"); 
  fetch('/szenario/routen') //Daten laden
    .then(res => {
      console.log("Fetch Antwort", res);
      return res.json();
    })
    .then(data => {
      console.log("Route Daten:", data);
      data.forEach(({ fahrzeug_id, punkte }) => {
        if (punkte && punkte.length > 1) {
          bewegeFahrzeugEntlangRoute(fahrzeug_id, punkte); //Funktion ausführen
        }
      });
    })
    .catch(err => { //Falls Fehler diese Ausgabe
      console.error("Fehler beim Laden der Routen:", err);
    });
}
function szenarioFortsetzen() { //Funktion hinter szenario Fortsetzen Button
  fetch('/szenario/routenzwei')
    .then(res => res.json())
    .then(data => {
      data.forEach(({ fahrzeug_id, punkte }) => {
        if (fahrzeug_id === 'HS06') {
          // HS06 soll beim dritten Routenpunkt starten
          bewegeFahrzeugEntlangRoute(fahrzeug_id, punkte, 3); 
        } else {
          // Alle anderen Normal
          bewegeFahrzeugEntlangRoute(fahrzeug_id, punkte);
        }
      });
    })
    .catch(err => { //Falls Fehler diese Ausgabe
      console.error("Fehler beim Laden der Routen:", err);
    });
}
</script>

<style>
  .mobile-nav {
    position: fixed;
    bottom: 0;
    left: 0;
    width: 100vw;
    height: 60px; /* or however tall your nav is */
    background-color: #ffffff;
    border-top: 1px solid #004f6e;
    box-shadow: 0 -1px 10px rgba(0, 79, 110, 0.3);
    z-index: 1000;
    pointer-events: auto;
}
@media (min-width: 992px) {
    .nav-link:not(.active):hover {
      background-color:#e7f1ff;
      color:#0d6efd !important;
    }
  }

.nav-link.active {
    font-weight:600;
    border-bottom:2px solid #0d6efd;   /* slightly darker than hover */
}
  .sidebar .nav-link {
      position: relative;               /* anchor becomes positioning context */
  }

  /* turn the arrow blue when the item is active */
  .sidebar .nav-link.active::after {
      color:rgb(255, 255, 255);                   /* Bootstrap “primary” */
  }

  @media (min-width: 992px) {
      .sidebar .nav-link:not(.active):hover::after {
          color:#0d6efd;
      }
  }
</style>