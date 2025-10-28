<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Kartenansicht</title>

    <!-- Leaflet CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.pm@latest/dist/leaflet.pm.css" />
    <style>
        /* Eigene Styles */
        #map {
            width: 100%;
            max-height: 100vh;
            aspect-ratio: 1318 / 768;
            border: none;
        }
        .legend-icon {
            width: 30px;
            height: 30px;
        }
        #map-legend {
    display: flex;
    flex-wrap: wrap;
    gap: 1rem;
    background: #f9f9f9;
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 0.5rem 1rem;
    font-size: 14px;
    align-items: center;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.legend-icon {
    width: 24px;
    height: 24px;
}

.legend-dot {
    width: 14px;
    height: 14px;
    border-radius: 50%;
    display: inline-block;
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
@@media (max-width: 768px) {
    #map-legend {
        flex-direction: row;         /* keep in a line */
        flex-wrap: wrap;             /* allow wrap if needed */
        justify-content: flex-start; /* align items left */
        align-items: center;
        font-size: 12px;
        padding: 0.25rem 0.5rem;
        gap: 2rem;
    }

    .legend-item {
        flex-shrink: 0;              /* don't shrink icon/text */
        gap: 0.4rem;
    }

    .legend-icon {
        width: 18px;
        height: 18px;
    }

    .legend-dot {
        width: 10px;
        height: 10px;
    }


    .leaflet-tooltip {
        font-size: 9px;
        padding: 2px 4px;
    }

    .marker-label {
        font-size: 8px;
        line-height: 1;
        margin-top: -5px;
            color: white;
    white-space: nowrap;
    font-weight: bold;
    background: black;
    padding: 2px 2px;
  border-radius: 3px;
  box-shadow: 0 0 3px rgba(0,0,0,0.2);
    }


    .marker-wrapper {
        position: relative;
        display: inline-block;
        text-align: center;
    }

    .warn-badge {
        top: -1px;
        right: -1px;
        font-size: 9px;
        width: 12px;
        height: 12px;
        line-height: 12px;
    }
}

    </style>
</head>
<body>

    <div id="map"></div>

    <div id="map-legend">
    <div class="legend-item">
        <img src="/images/stapler.svg" alt="Stapler" class="legend-icon" />
        <span>Hochregalstapler</span>
    </div>
    <div class="legend-item">
        <img src="/images/ghw.png" alt="Hubwagen" class="legend-icon" />
        <span>Gabelhubwagen</span>
    </div>
    <div class="legend-item">
        <div class="legend-dot red-dot"></div>
        <span>Meldung</span>
    </div>
</div>


    <!-- Leaflet Eibinden -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://unpkg.com/leaflet.pm@latest/dist/leaflet.pm.min.js"></script> 
    <script>
        const map = L.map('map', {
            crs: L.CRS.Simple,
            minZoom: -1,
            maxZoom: 1,
            zoomControl: true,
            scrollWheelZoom: false,
            doubleClickZoom: false,
            touchZoom: true,
            zoomSnap: 0,
            zoomDelta: 0.5,
            dragging: true,
            attributionControl: false
        });

        const bounds = [[0, 0], [768, 1318]];
        const image = L.imageOverlay('/images/Szenario.png', bounds).addTo(map); //Lade der Karte
        map.setMaxBounds(bounds);
        //Mapgröße anpassen, dass nicht zu groß
        function fitImageExactly() {
            const container = document.getElementById('map');
            const containerWidth = container.clientWidth;
            const containerHeight = container.clientHeight;

            const scaleX = containerWidth / 1318;
            const scaleY = containerHeight / 768;
            const scale = Math.max(scaleX, scaleY);
            const zoom = Math.log2(scale);

            map.setView([768 / 2, 1318 / 2], zoom);
            map.options.minZoom = zoom;
            map.setZoom(zoom);
        }

        fitImageExactly();
        window.addEventListener('resize', fitImageExactly);

        map.on('drag', function () { //Drag zum erstellen der Lagerplätze, ist deaktiviert
            const current = map.getCenter();
            const clampedLat = Math.min(bounds[1][0], Math.max(bounds[0][0], current.lat));
            map.panTo([clampedLat, current.lng], { animate: false });
        });

        const markerMap = {};
        function isMobile() {
            return window.innerWidth <= 768;
        }

window.routeLayer = L.polyline([], { color: 'blue', weight: 3 }).addTo(map); //Strich anlegen
var aktiveFahrzeugId = null; //überprüfen, ob  schon eine Route geladen ist
function zeigeRoute(fahrzeugId) { //Funktion zum Routeladen
    if (!window.routeLayer) {
        window.routeLayer = L.polyline([], { color: 'blue', weight: 3 }).addTo(map); //zeichen
    }
    const collapseId = `collapse${fahrzeugId}`;
    const collapseEl = document.getElementById(collapseId);
    const bsCollapse = new bootstrap.Collapse(collapseEl, {
        toggle: false // Wir steuern das Öffnen/Schließen manuell
    });

    // Wenn gleiche Fahrzeug-ID wie vorher, Route ausblenden und Info rechts  schließen
    if (aktiveFahrzeugId === fahrzeugId) {
        routeLayer.setLatLngs([]);
        aktiveFahrzeugId = null;

        // Info rechts einklappen
        bsCollapse.hide();

        return;
    }

    // Falls Info rechts offen, schließen
    if (aktiveFahrzeugId !== null) {
        const vorherigesCollapse = document.getElementById(`collapse${aktiveFahrzeugId}`);
        if (vorherigesCollapse) {
            new bootstrap.Collapse(vorherigesCollapse, { toggle: false }).hide();
        }
    }

    // Route anzeigen mit fetch
    fetch(`/route/${fahrzeugId}`)
        .then(res => {
            if (!res.ok) {
                return res.json().then(errorData => {
                    console.error('Server-Fehler beim Laden der Route:', errorData.fehler || 'Unbekannter Fehler');
                    routeLayer.setLatLngs([]);
                    aktiveFahrzeugId = null;
                    throw new Error(errorData.fehler || 'Serverfehler');
                });
            }
            return res.json();
        })
        .then(data => {
            if (!data.route || data.route.length === 0) {
                console.warn('Keine Routen-Daten erhalten oder Route leer:', data.fehler);
                routeLayer.setLatLngs([]);
                aktiveFahrzeugId = null;
                return;
            }

            const routePoints = data.route.map(p => [p.y, p.x]);
            routeLayer.setLatLngs(routePoints);
            aktiveFahrzeugId = fahrzeugId;

            // Info rechts ausklappen
            bsCollapse.show();
        })
        .catch(err => { //Fehlersuche
            console.error('Fehler beim Laden der Route:', err);
            routeLayer.setLatLngs([]);
            aktiveFahrzeugId = null;

            bsCollapse.hide();
        });
}
function ladeFahrzeuge() { //Fahrzeuge in Echtzeit laden
    fetch('/fahrzeuge-daten')
        .then(res => res.json())
        .then(fahrzeuge => {
            fahrzeuge.forEach(fz => {
            if (aktiveFahrzeugId === fz.fahrzeug_id) {
            // Fahrzeug wird gerade animiert, Position nicht überschreiben
                return;
            }
            const pos = [fz.y, fz.x];

            let iconUrl; //Icons der Fahrzeuge anpassen, mit default
            switch (fz.type) {
                case 'Hochregalstapler':
                    iconUrl = '/images/stapler.svg';
                    break;
                case 'Gabelhubwagen ':
                    iconUrl = '/images/ghw.png';
                    break;
                case 'Standard':
                default:
                    iconUrl = '/images/ghw.png';
                    break;
            }
            //anpassen für Mobile
            const iconSize = isMobile() ? [30, 30] : [45, 45];
            const iconAnchor = isMobile() ? [0, 0] : [32, 32];
            //meldung
            const hasMeldung = fz.meldung && fz.meldung.trim().toLowerCase() !== 'keine';
            const meldungText = fz.meldung?.toLowerCase() || '';

            const isBatteryIssue = meldungText.includes('batterie') || meldungText.includes('akku');

            const meldungIcon = isBatteryIssue
                ? '<i class="bi bi-battery"></i>'
                : '!';

            const icon = L.divIcon({
            html: `
                <div class="marker-wrapper">
                <img src="${iconUrl}" width="${iconSize[0]}" height="${iconSize[1]}" />
                ${hasMeldung ? `<div class="warn-badge">${meldungIcon}</div>` : ''}
                <div class="marker-label">${fz.fahrzeug_id}</div>
                </div>
            `,
            iconSize: iconSize,
            iconAnchor: iconAnchor,
            className: ''
            });

            //Mobile
            isOnMobile = isMobile();
            if (markerMap[fz.fahrzeug_id]) {
            markerMap[fz.fahrzeug_id].setLatLng(pos);
            markerMap[fz.fahrzeug_id].setIcon(icon);
            } else {
            const tooltipText = fz.meldung && fz.meldung.trim() !== 'Keine' ? fz.meldung : `Status: ${fz.status}`;
            const offset = isOnMobile ? [0, 0] : [-15, -45];
            const marker = L.marker(pos, { icon })
            .bindTooltip(tooltipText, {
            offset: offset,
            permanent: false,
            direction: 'center',
            className: fz.meldung && fz.meldung.trim() !== 'Keine' ? 'meldung' : ''
            })

            .addTo(map)
            .on('click', () => zeigeRoute(fz.fahrzeug_id));//zeige Route auf onlclik

            markerMap[fz.fahrzeug_id] = marker;
                }
            });
        });
}

ladeFahrzeuge(); //Fahrzeuge werden gelaaden
//setInterval(ladeFahrzeuge, 50); //Echzeitaktualisierung aus für Szenario 

const markerLayer = L.layerGroup().addTo(map);

//Für Drag an Drop der Lagerplätze
//function sendeNeueLagerplatzPosition(lagerplatz_id, x, y) {
//    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
//
//          fetch('/lagerplatz/' + lagerplatz_id + '/update-position', {
//            method: 'POST',
//                headers: {
//                    'Content-Type': 'application/json',
//                    'X-CSRF-TOKEN': csrfToken
//                },
//                body: JSON.stringify({ x: x, y: y })
//            })
//            .then(response => {
//                if (!response.ok) {
//                    throw new Error('Fehler beim Senden: ' + response.status);
//                }
//                return response.json();
//            })
//            .then(data => {
//                console.log('Lagerplatz aktualisiert:', data);
//            })
//           .catch(error => {
//                console.error('Fehler beim Aktualisieren:', error);
//            });
//        }

function ladeLagerplaetze() { //Laden der Lagerplätze
    fetch('/lagerplaetze')
        .then(response => response.json())
        .then(lagerplaetze => {
        markerLayer.clearLayers(); // vorherige Rechtecke entfernen
        lagerplaetze.forEach(lager => {
            if(lager.type === 'unsichtbar'){ //Für Szenario um Route für HS04 darzustellen
                return;
            }
            const bounds = [
            [lager.y, lager.x],
            [lager.y + lager.hoehe, lager.x + lager.breite]
            ];

            const rechteck = L.rectangle(bounds, {
                color: '#808080',
                weight: 1,
                fillOpacity: 0.4
            }).addTo(markerLayer)
            .bindPopup(`${lager.lagerplatz_id} ${lager.belegt}/${lager.anzahl}`);

            //rechteck.pm.enableLayerDrag();
            //Drag and drop deaktiviert
            //rechteck.on('pm:dragend', e => {
            //    const neueBounds = e.target.getBounds();
            //    const neueX = Math.round(neueBounds.getSouthWest().lng);
            //    const neueY = Math.round(neueBounds.getSouthWest().lat);
            //    sendeNeueLagerplatzPosition(lager.lagerplatz_id, neueX, neueY);
            //});
        });
    });
}

ladeLagerplaetze();
setInterval(ladeLagerplaetze, 10000); //Laden der Lagerplätze und Echtzeitaktualisierung 

map.on('click', e => { // ANzeigen der Koordinaten in Konsole
    console.log("Koordinaten:", e.latlng);
});

//Anzeigen der Koordinaten auf der Karte
//map.on('click', function(e) {
//    L.popup()
//    .setLatLng(e.latlng)
//    .setContent("y: " + e.latlng.lat.toFixed(2) + "<br>x: " + e.latlng.lng.toFixed(2))
//    .openOn(map);
//});

document.addEventListener('DOMContentLoaded', function () { 
  const urlParams = new URLSearchParams(window.location.search);
  const fahrzeugId = urlParams.get('fahrzeug') || urlParams.get('fahrzeug_id');
  if (fahrzeugId && typeof zeigeRoute === 'function') {
    setTimeout(() => {
      zeigeRoute(fahrzeugId);

      // Nach kurzem Delay scrollen (damit DOM fertig ist)
      setTimeout(() => {
        const row = document.querySelector(`tr[data-fahrzeug_id="${fahrzeugId}"]`);
        if (row) {
          row.scrollIntoView({ behavior: "smooth", block: "center" });
        }
      }, 250);
    }, 200);
  }
});

</script>
    
</body>
</html>

