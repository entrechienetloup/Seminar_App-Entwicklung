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
<div class="container {{ isset($compact) && $compact ? 'py-2' : 'py-4' }}">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @foreach($fahrzeuge as $fzg)
    @php $collapseId = 'collapse' . $fzg['fahrzeug_id']; @endphp
    <div class="{{ isset($compact) && $compact ? 'mb-2' : 'mb-3' }}">
      <div class="d-flex align-items-center border rounded shadow-sm 
                  {{ isset($compact) && $compact ? 'p-2 small' : 'p-3' }}">
        <div class="flex-grow-1">
          <h5 class="{{ isset($compact) && $compact ? 'fs-6 mb-1' : '' }}">
            <span class="text-decoration-none text-dark">
                {{ $fzg['fahrzeug_id'] }}
            </span>
{{-- id als index einsetzen um die Daten zu holen --}}
          </h5>
          <div class="d-flex align-items-center flex-wrap gap-2 small ms-0 pt-2">
            @php
              $status = $fzg['status'];
              switch ($status) {
                case 'aktiv':
                  $badgeClass = 'bg-success';
                  break;
                case 'lädt':
                  $badgeClass = 'bg-primary';
                  break;
                case 'inaktiv':
                  $badgeClass = 'bg-secondary';
                  break;
                case 'außer betrieb':
                  $badgeClass = 'bg-danger';
                  break;
                default:
                  $badgeClass = 'bg-dark';
              }// Farbe der unterschiedlichen Staten 

              $ladestand = intval($fzg['ladestand']);
              $status = trim(mb_strtolower($fzg['status']));

              if ($status === 'lädt') {
                  $batteryIcon = 'bi-battery-charging';
              } elseif ($ladestand >= 75) {
                  $batteryIcon = 'bi-battery-full';
              } elseif ($ladestand >= 20) {
                  $batteryIcon = 'bi-battery-half';
              // } elseif ($ladestand >= 15) {
              //     $batteryIcon = 'bi-battery-low';
              } else {
                  $batteryIcon = 'bi-battery';
              }

              $batteryClass = $ladestand < 20 ? 'text-danger fw-bold' : '';
              // Batterie mit/ohne Blitzzeichen, niedrig(rot)/normal(grau)
            @endphp

            {{-- Farbe an Status anwenden --}}
            <span class="badge {{ $badgeClass }}">{{ $fzg['status'] }}</span>


            {{-- Farbe an Battery anwenden --}}
            <span class="{{ $batteryClass }}">
            <i class="bi {{ $batteryIcon }} me-1"></i>{{ $ladestand }}%
            </span>


            {{-- Wenn Auftrag vorhanden mit Box-Icon anzeigen --}}
            @if($fzg['akt_ta'])
              <span>
                <i class="bi bi-box-seam me-1"></i>{{ $fzg['akt_ta'] }}
              </span>
            @endif
          </div>
        </div>
        <button class="toggleActionsBtn btn btn-outline-secondary btn-sm"
                data-bs-toggle="collapse"
                data-bs-target="#{{ $collapseId }}"
                aria-expanded="false"
                aria-controls="{{ $collapseId }}"
                data-fahrzeug-id="{{ $fzg['fahrzeug_id'] }}">
            Ausklappen
        </button>
      </div>
      <div class="collapse mt-2" id="{{ $collapseId }}">
        <div class="card card-body {{ isset($compact) && $compact ? 'p-2 small' : '' }}">
          <!-- Fahrzeug_ID,Status,Zeitstempel,Ladestand,Akkuzustand,Aktueller Transportauftrag,Meldung
 -->
          <table>
            <thead>
              <tr>
                <th>Fahrzeug-ID</th>
                <td>{{ $fzg['fahrzeug_id'] }}</td>
              </tr>
              <tr>
                <th>Status</th>
                <td>{{ $fzg['status'] }}</td>
              </tr>
              <tr>
                <th>Zeitstempel</th>
                <td>{{ $fzg['zeitstempel'] }}</td>
              </tr>
              <tr>
                <th>Ladestand</th>
                <td>{{ $fzg['ladestand'] }}</td>
              </tr>
              <tr>
                <th>Akkuzustand</th>
                <td>{{ $fzg['akkuzustand'] }}</td>
              </tr>
              <tr>
                <th>Transportauftrag-ID</th>
                <td>{{ $fzg['akt_ta'] }}</td>
              </tr>
              <tr>
                <th>Meldung</th>
                <td style="{{ $fzg['meldung'] !== 'Keine' ? 'color: red; font-weight: bold;' : '' }}">
                {{ $fzg['meldung'] }}
                </td>
              </tr>
            </tbody>
          </table>

          <!-- Buttons -->
          <div class="mt-2 d-flex flex-wrap gap-2">
            {{-- Route anpassen --}}
            <a class="routeBtn btn btn-secondary btn-sm"
               onclick="Routenpunkteanzeigen('{{ $fzg['fahrzeug_id'] }}')">Route anpassen</a>

           {{--<button class="routeBtn btn btn-secondary btn-sm"
                    onclick="sendeAusgewaehlteRoute('{{ $fzg['fahrzeug_id'] }}')">
                Route speichern 
            </button>--}}

          <button class="routeBtn btn btn-secondary btn-sm"
                  onclick="SzenarioRouteSpeichern('{{ $fzg['fahrzeug_id'] }}')"> {{--für Szenario--}}
                Route speichern 
            </button> 
          
          {{-- Fahrzeug stoppen --}}
          <form action="{{ route('fahrzeug.fahrenStoppen', $fzg['fahrzeug_id']) }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-secondary btn-sm">Stoppen</button>
          </form>


          </div>
        </div>
      </div>
      <script>
        // notwendig, sonst Einklappen/Ausklappen-Button nicht korrekt
        $(document).ready(function() {
          $('.collapse').on('show.bs.collapse', function () {
            var btn = $('[data-bs-target="#' + this.id + '"]');
            btn.text('Einklappen');
          });
          $('.collapse').on('hide.bs.collapse', function () {
            var btn = $('[data-bs-target="#' + this.id + '"]');
            btn.text('Ausklappen');
          });
        });

        function getQueryParam(name) {
            const params = new URLSearchParams(window.location.search);
            return params.get(name);
        }

        $(document).ready(function() {
            const openFzg = getQueryParam('fahrzeug_id');
            if (openFzg) {
                // Dein Collapse-Div hat die ID "collapseHS01", "collapseHS02", ...
                const collapseId = '#collapse' + openFzg;
                const $collapse = $(collapseId);

                if ($collapse.length) {
                    // Mit Bootstrap 5: Collapse öffnen
                    var bsCollapse = bootstrap.Collapse.getOrCreateInstance($collapse[0]);
                    bsCollapse.show();

                    // Button-Text ggf. anpassen
                    // (optional)
                    const $btn = $(`button[data-bs-target="${collapseId}"]`);
                    if ($btn.length) {
                        $btn.text('Einklappen');
                    }

                    // Scrollen (optional)
                    $('html, body').animate({
                        scrollTop: $collapse.offset().top - 60
                    }, 300);
                }
            }
        });

      </script>
    </div>
  @endforeach
  <script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggleActionsBtn').forEach(btn => {
            btn.addEventListener('click', function () {
                const fahrzeugId = this.dataset.fahrzeugId;
                zeigeRoute(fahrzeugId);
            });
        });
    });
  let ausgewaehltePunkte = [];
  let routenpunktLayer = L.layerGroup().addTo(map);
  let aktuelleFahrzeugId = null;
  let routeAngezeigt = false;

  window.Routenpunkteanzeigen = function (fahrzeugId) { // wenn Route anpassen
     // Entfernt angezeigte Routen
    if (window.routeLayer) {
        map.removeLayer(window.routeLayer);
        window.routeLayer = null;
    } else {
        // Neue Route wird angezeift
            fetch(`/route/${fahrzeugId}`)
            .then(res => {
                if (!res.ok) {
                    return res.json().then(errorData => {
                        console.error('Server-Fehler beim Laden der Route:', errorData.fehler || 'Unbekannter Fehler');
                        if (window.routeLayer) {
                            window.routeLayer.setLatLngs([]);
                        }
                        return Promise.reject(errorData.fehler || 'Serverfehler');
                    });
                }
                return res.json();
            })
            .then(data => {
                if (!data.route || data.route.length === 0) {
                    console.warn('Keine Routen-Daten erhalten oder Route leer:', data.fehler);
                    if (window.routeLayer) {
                        window.routeLayer.setLatLngs([]);
                    }
                    return;
                }
                const latlngs = data.route.map(p => [p.y, p.x]);
                window.routeLayer = L.polyline(latlngs, { color: 'blue', weight: 3 }).addTo(map);
            })
            .catch(err => {
                console.error('Fehler beim Laden der Route:', err);
            });
    }

    //Beim erneuten ankilcken werden Routenpunkte entfernt
    if (aktuelleFahrzeugId === fahrzeugId) {
        routenpunktLayer.clearLayers();
        if (map.hasLayer(routenpunktLayer)) {
            map.removeLayer(routenpunktLayer);
        }

        // Fahrzeuge wieder anzeigen
        for (const key in markerMap) {
            if (!map.hasLayer(markerMap[key])) {
                markerMap[key].addTo(map);
            }
        }

        // wieder zurücksetzen
        aktuelleFahrzeugId = null;
        ausgewaehltePunkte = [];

        return;
    }

    //Fahrzeuge ausbelden
    for (const key in markerMap) {
        if (map.hasLayer(markerMap[key])) {
            map.removeLayer(markerMap[key]);
        }
    }

    aktuelleFahrzeugId = fahrzeugId;
    ausgewaehltePunkte = [];
    routenpunktLayer.clearLayers();
    const fahrzeugMarker = markerMap[fahrzeugId];
    if (fahrzeugMarker) {
        const fahrzeugPos = fahrzeugMarker.getLatLng();
        const gelbMarker = L.circleMarker(fahrzeugPos, {
            radius: 3,
            color: 'black',
            fillColor: 'black',
            fillOpacity: 1.0,
        }).addTo(routenpunktLayer);
    }
    //Routenpunkte werden geladen und als Kreis dargestellt
    fetch('/routenpunkte')
        .then(res => res.json())
        .then(punkte => {
            punkte.forEach(p => {
                const marker = L.circleMarker([p.y, p.x], {
                    radius: 5,
                    color: '#007bff',
                    fillColor: '#007bff',
                    fillOpacity: 0.8,
                }).addTo(routenpunktLayer);

                marker.bindTooltip(p.routenpunkte_id);
                // Wenn angeklickt gtün und and FUnktion senden
                marker.on('click', () => {
                    if (!ausgewaehltePunkte.includes(p.routenpunkte_id)) {
                        marker.setStyle({ color: 'green', fillColor: 'green' });
                        ausgewaehltePunkte.push(p.routenpunkte_id);
                    }
                });
            });

            // RoutenpunktLayer zur Karte hinzufügen
            if (!map.hasLayer(routenpunktLayer)) {
                routenpunktLayer.addTo(map);
            }
        });
};
window.sendeAusgewaehlteRoute = function (fahrzeugId = null) { //Route speichern mit Routenpunkte in Reihenfolge
  fahrzeugId = fahrzeugId || aktuelleFahrzeugId;

  fetch('/route/store', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
    },
    body: JSON.stringify({
      fahrzeug_id: fahrzeugId,
      routenpunkte: ausgewaehltePunkte.join(','),
    })
  })
  .then(res => res.json())
  .then(data => {
    alert(data.message);
    routenpunktLayer.clearLayers();

    // Fahrzeuge wieder anzeigen
    for (const key in markerMap) {
      if (!map.hasLayer(markerMap[key])) {
        markerMap[key].addTo(map);
      }
    }

    // Route anzeigen
    fetch(`/route/${fahrzeugId}`)
      .then(res => {
        if (!res.ok) throw new Error('Fehler beim Laden der Route');
        return res.json();
      })
      .then(data => {
        if (!data.route || data.route.length === 0) {
          console.warn('Keine Routen-Daten erhalten oder Route leer:', data.fehler);
          if (window.routeLayer) {
            window.routeLayer.setLatLngs([]);
          }
          return;
        }
        const latlngs = data.route.map(p => [p.y, p.x]);
        if (window.routeLayer) {
          window.routeLayer.setLatLngs(latlngs);
        } else {
          window.routeLayer = L.polyline(latlngs, { color: 'blue', weight: 3 }).addTo(map);
        }
      })
      .catch(err => {
        console.error(err);
      });

    // Auswahl zurücksetzen
    ausgewaehltePunkte = [];
    aktuelleFahrzeugId = null;
  })
  .catch(err => alert('Fehler: ' + err.message));
};
function aktualisiereMarkerStatus(fahrzeugId, meldung, status) { //Für Szenario
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

  // Warn-Badge anpassen, ohne Icon komplett neu zu setzen
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

    // Label-Klasse anpassen (fett + rot hinterlegt)
    const label = wrapper.querySelector('.marker-label');
    if (label) {
      label.classList.toggle('meldung-label', hasMeldung);
    }
  }

  // Tooltip vollständig neu setzen
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
function SzenarioRouteSpeichern(fahrzeugId) { //
  sendeAusgewaehlteRoute(fahrzeugId); 
  fetch('/fahrzeug/status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ fahrzeug_id: 'HS03', meldung: 'Nicht Erreichbar', status: 'nicht erreichbar' })
      })
      .then(() => aktualisiereMarkerStatus('HS03', 'Nicht Erreichbar', 'nicht erreichbar'));

      fahrzeugStopFlags.HS06 = true;

      fetch('/fahrzeug/status', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
          'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({ fahrzeug_id: 'HS06', meldung: 'Keine', status: 'aktiv' })
      })
      .then(() => aktualisiereMarkerStatus('HS06', 'Keine', 'aktiv'));
}
</script>
</div>
