@extends('layouts.app')

@section('title', 'Fahrzeuge')

@section('content')
<!-- always visible on mobile -->
<div class="container py-4">
  <div class="d-lg-none mb-2">
    <button class="btn btn-outline-info w-100" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapseMobile">
      <i class="bi bi-funnel-fill"></i> Filter
    </button>
    <!-- Filter Button -->
    <div class="collapse mt-2" id="filterCollapseMobile">
      <div class="card card-body">
        <div class="row g-2">
          <div class="col-md-2">
            <input type="text" id="filter-fahrzeug_id-mobile" class="form-control" placeholder="Fahrzeug-ID">
          </div>
          <div class="col-md-2">
            <select id="filter-status-mobile" class="form-select">
              <option value="">Status</option>
              <option value="aktiv">aktiv</option>
              <option value="inaktiv">inaktiv</option>
              <option value="außer Betrieb">außer Betrieb</option>
              <option value="lädt">lädt</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="text" id="filter-zeitstempel-mobile" class="form-control" placeholder="Zeitstempel">
          </div>
          <div class="col-md-2">
            <select id="filter-ladestand-mobile" class="form-select">
              <option value="">Ladestand</option>
              <option value="lt50">&lt;50 %</option>
              <option value="gte50">&ge;50 %</option>
            </select>
          </div>
          <div class="col-md-2">
            <select id="filter-akkuzustand-mobile" class="form-select">
              <option value="">Akkuzustand</option>
              <option value="lt50">&lt;50 %</option>
              <option value="gte50">&ge;50 %</option>
            </select>
          </div>
          <div class="col-md-2">
            <input type="text" id="filter-akt_ta-mobile" class="form-control" placeholder="Transportauftrag-ID">
          </div>
          <div class="col-md-2">
            <button id="applyFilterBtnMobile" class="btn btn-info w-100">Filter anwenden</button>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- always visible on desktop -->
  <div class="d-none d-lg-block mb-3">
    <div class="card card-body">
      <div class="row g-2">
        <div class="col-md-2">
          <input type="text" id="filter-fahrzeug_id-desktop" class="form-control" placeholder="Fahrzeug-ID">
        </div>
        <div class="col-md-2">
          <select id="filter-status-desktop" class="form-select">
            <option value="">Status</option>
            <option value="aktiv">aktiv</option>
            <option value="inaktiv">inaktiv</option>
            <option value="außer Betrieb">außer Betrieb</option>
            <option value="lädt">lädt</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="text" id="filter-zeitstempel-desktop" class="form-control" placeholder="Zeitstempel">
        </div>
        <div class="col-md-2">
          <select id="filter-ladestand-desktop" class="form-select">
            <option value="">Ladestand</option>
            <option value="lt50">&lt;50 %</option>
            <option value="gte50">&ge;50 %</option>
          </select>
        </div>
        <div class="col-md-2">
          <select id="filter-akkuzustand-desktop" class="form-select">
            <option value="">Akkuzustand</option>
            <option value="lt50">&lt;50 %</option>
            <option value="gte50">&ge;50 %</option>
          </select>
        </div>
        <div class="col-md-2">
          <input type="text" id="filter-akt_ta-desktop" class="form-control" placeholder="Transportauftrag-ID">
        </div>
        <div class="col-md-2">
          <button id="applyFilterBtnDesktop" class="btn btn-info w-100">Filter anwenden</button>
        </div>
      </div>
    </div>
  </div>

    

<!-- Reset-Button -->
    <div id="resetFilterContainer" class="mb-2" style="display:none;">
      <button id="resetFilterBtn" class="btn btn-outline-secondary btn-sm">Filter zurücksetzen</button>
    </div>

    <template id="fahrzeug-row-template">
      <tr data-fahrzeug_id="_fahrzeug_id_">
        <td>
          <span class="noEdit fahrzeug_id">_fahrzeug_id_</span>
          <input class="edit fahrzeug_id form-control" type="text" value="_fahrzeug_id_" style="display:none;">
        </td>
        <td>
          <span class="noEdit status">_status_</span>
          <input class="edit status form-control" type="text" value="_status_" style="display:none;">
        </td>
        <td>
          <span class="noEdit zeitstempel">_zeitstempel_</span>
          <input class="edit zeitstempel form-control" type="text" value="_zeitstempel_" style="display:none;">
        </td>
        <td>
          <span class="noEdit ladestand">_ladestand_</span>
          <input class="edit ladestand form-control" type="number" step="any" value="_ladestand_" style="display:none;">
        </td>
        <td>
          <span class="noEdit akkuzustand">_akkuzustand_</span>
          <input class="edit akkuzustand form-control" type="number" step="any" value="_akkuzustand_" style="display:none;">
        </td>
        <td>
          <span class="noEdit akt_ta">_akt_ta_</span>
          <input class="edit akt_ta form-control" type="text" value="_akt_ta_" style="display:none;">
        </td>
        <td>
          <span class="meldung" style="_meldungStyle_">_meldung_</span>
        </td>
        <td>
          <button class="toggleActionsBtn btn btn-light btn-sm">Ausklappen</button>
        </td>
      </tr>
    </template>

    <style>
    th{ 
      cursor: pointer;
      color:#fff;
    }
    </style>
  <!-- thead immer sichtbar, tbody durch Funktion aufgebaut -->
    <table id="fahrzeugTable" class="table table-striped d-none d-lg-table">
      <thead>
        <tr class="bg-info">
        <th data-colname="fahrzeug_id" data-order="desc">Fahrzeug-ID &#9650</th>
        <th data-colname="status" data-order="desc">Status &#9650</th>
        <th data-colname="zeitstempel" data-order="desc">Zeitstempel &#9650</th>
        <th data-colname="ladestand" data-order="desc">Ladestand &#9650</th>
        <th data-colname="akkuzustand" data-order="desc">Akkuzustand &#9650</th>
        <th data-colname="akt_ta" data-order="desc">Transportauftrag-ID &#9650</th>
        <th data-colname="meldung" data-order="desc">Meldung</th>
        <th>Aktion</th>
        </tr>
      </thead>
      <tbody id="fahrzeugTableBody"></tbody>
    </table>
    <!-- Mobile Ansicht -->
    <div id="fahrzeugCardContainer" class="d-lg-none"></div>

<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var fahrzeugArray = @json($fahrzeuge);
  let offeneButtonZeilen = {};
  let lastSortCol = null;
  let lastSortOrder = null;

  function htmlEncode(str) {
    return String(str ?? '').replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }// htmlEncode verhindert Sicherheitslücken wie XSS-Attacken und Darstellungsfehler, wenn Daten als HTML eingefügt werden.

  function buildFahrzeugTable(data) {
    $('.action-row').remove();
    offeneButtonZeilen = {};
    const table = document.getElementById('fahrzeugTableBody');
    const tpl   = document.getElementById('fahrzeug-row-template').innerHTML;
    table.innerHTML = '';
    data.forEach(function(row) {
      var tr = tpl
        .replace(/_fahrzeug_id_/g, htmlEncode(row.fahrzeug_id))
        .replace(/_status_/g,        htmlEncode(row.status))
        .replace(/_zeitstempel_/g,   htmlEncode(row.zeitstempel))
        .replace(/_ladestand_/g,     htmlEncode(row.ladestand))
        .replace(/_akkuzustand_/g,   htmlEncode(row.akkuzustand))
        .replace(/_akt_ta_/g,        htmlEncode(row.akt_ta))
        .replace(/_meldung_/g,       htmlEncode(row.meldung))//g: global, i. e. alle Vorkommen ersetzen
        .replace(/_meldungStyle_/g, row.meldung && row.meldung !== 'Keine' ? 'color:red; font-weight:bold;' : '')
      table.innerHTML += tr;
      /*
      let table = document.getElementById('fahrzeugTableBody');
      let tr = "<tr><td>HS01</td><td>aktiv</td></tr>";
      table.innerHTML += tr;
      Vorher:

      html
      Code kopieren
      <tbody id="fahrzeugTableBody">
        <!-- evtl. schon Zeilen -->
      </tbody>
      Nachher:

      html
      Code kopieren
      <tbody id="fahrzeugTableBody">
        <!-- evtl. schon Zeilen -->
        <tr><td>HS01</td><td>aktiv</td></tr>
      </tbody>
      */
    });
  }
  function buildFahrzeugCards(data) {
    const container = document.getElementById('fahrzeugCardContainer');
    container.innerHTML = '';

    data.forEach(f => {
      const card = document.createElement('div');
      card.className = 'card mb-3 shadow-sm';
      card.innerHTML = `
        <div class="card-body">
          <h6 class="card-title mb-2 fw-bold"><i class="bi bi-truck me-1"></i> ${f.fahrzeug_id}</h6>
          <p class="mb-1"><strong>Status:</strong> ${htmlEncode(f.status)}</p>
          <p class="mb-1"><strong>Zeitstempel:</strong> ${htmlEncode(f.zeitstempel)}</p>
          <p class="mb-1"><strong>Ladestand:</strong> ${htmlEncode(f.ladestand)}</p>
          <p class="mb-1"><strong>Akkuzustand:</strong> ${htmlEncode(f.akkuzustand)}</p>
          <p class="mb-2"><strong>TA:</strong> ${htmlEncode(f.akt_ta)}</p>
          <p class="mb-3" style="${f.meldung && f.meldung !== 'Keine' ? 'color:red; font-weight:bold;' : ''}">
            <strong>Meldung:</strong> ${htmlEncode(f.meldung)}
          </p>
          <div class="d-flex flex-wrap gap-2 justify-content-end">
            <a href="/dashboard?fahrzeug=${f.fahrzeug_id}" class="btn btn-sm btn-secondary" data-fahrzeug_id="${f.fahrzeug_id}">Anzeigen</a>
            <button class="umschaltenBtn btn btn-sm btn-secondary" data-fahrzeug_id="${f.fahrzeug_id}">
              ${f.status === 'aktiv' ? 'Deaktivieren' : 'Aktivieren'}
            </button>
            <a href="/bearbeiten#routenpunkt-hinzufuegen" class="btn btn-sm btn-secondary routeBtn" data-fahrzeug_id="${f.fahrzeug_id}">Route</a>
            <button class="btn btn-sm btn-secondary fahrenStoppenBtn" data-fahrzeug_id="${f.fahrzeug_id}">
              ${f.status === 'aktiv' ? 'Stoppen' : 'Weiterfahren'}
            </button>
          </div>
        </div>
      `;
      container.appendChild(card);
    });
  }

/*
let arr = [3, 1, 4, 2];
arr.sort((a, b) => a - b);

wenn a < b, a - b < 0, a kommt vor b
*/
  function sortiereFahrzeugArray(arr, col, order) {
    arr.sort(function(a, b) {
      let va = a[col] ?? '', vb = b[col] ?? '';
      if (col === 'ladestand' || col === 'akkuzustand') {
        va = parseInt(va, 10) || 0;
        vb = parseInt(vb, 10) || 0;
        return order === 'asc' ? va - vb : vb - va;
      }
      if (col === 'fahrzeug_id') {
        return order === 'asc'
          ? va.localeCompare(vb, undefined, {numeric: true, sensitivity: 'base'})
          : vb.localeCompare(va, undefined, {numeric: true, sensitivity: 'base'});
          /*
          string.localeCompare(compareString, locales, options)
          locales (Sprache/Region)
          options:
          {numeric: true} auch Zahlen in den Strings sinnvoll sortiert.
          Z. B. wird dann HS2 vor HS10 sortiert, nicht wie bei reinem Stringvergleich ("HS10" wäre sonst vor "HS2").
          sensitivity: 'base' heißt: Groß- und Kleinschreibung wird ignoriert.*/
      }
      va = va.toString().toLowerCase();
      vb = vb.toString().toLowerCase();
      if (va < vb) return order === 'asc' ? -1 : 1;
      if (va > vb) return order === 'asc' ? 1 : -1;
      return 0;
    });
  }

  function updateHeaderSortIcons(activeCol, order) {
    $('#fahrzeugTable th[data-colname]').each(function(){
      let col = $(this).data('colname');
      let txt = $(this).text().replace(/[▲▼]/,'').trim();
      if (col === activeCol) {
        $(this).html(txt + (order==='asc' ? ' ▲' : ' ▼'));
      } else {
        $(this).html(txt);
        $(this).data("order", "asc");
      }
    });
  }

  $(function() {
    buildFahrzeugTable(fahrzeugArray);
    buildFahrzeugCards(fahrzeugArray);
    $('#fahrzeugTable th[data-colname]').on('click', function() {
      const $th = $(this);
      const col = $th.data('colname');
      const cur = $th.data('order') || 'asc';
      const next = cur === 'asc' ? 'desc' : 'asc';
      lastSortCol = col;
      lastSortOrder = next;
      let arr = fahrzeugArray.slice();
      sortiereFahrzeugArray(arr, col, next);
      updateHeaderSortIcons(col, next);
      buildFahrzeugTable(arr);
      buildFahrzeugCards(arr);
      $th.data('order', next);
    });
  });

  $(document).on('click', '.toggleActionsBtn', function() {
    const $btn = $(this);
    const $tr = $btn.closest('tr');// am nächstliegenden tr
    const fahrzeug_id = $tr.data('fahrzeug_id');
    const fahrzeug = fahrzeugArray.find(f => f.fahrzeug_id === fahrzeug_id);
    if (!fahrzeug) return;
    if (offeneButtonZeilen[fahrzeug_id]) {//offeneButtonZeilen: Zeile wo sich die Buttons befinden
    // Wenn man nochmal auf toggleActionsBtn klickt UND wenn "truthy" i. e. oBZ existiert wird stattdessen dieser Block ausgeführt
      offeneButtonZeilen[fahrzeug_id].remove();
      delete offeneButtonZeilen[fahrzeug_id];
      $btn.text('Ausklappen');// Text aktualisieren
      return;
    }
    $btn.text('Einklappen');
    const $row = $(`
      <tr class="action-row" data-action-id="${fahrzeug_id}">
        <td colspan="8" class="text-end" style="background:#f6f6f6;">
          <span style="font-weight:bold;">${fahrzeug_id}</span>
          <a href="/dashboard?fahrzeug_id=${fahrzeug_id}" class="btn btn-secondary btn-sm" data-fahrzeug_id="${fahrzeug_id}">Anzeigen</a>
          <button class="umschaltenBtn btn btn-secondary btn-sm" data-fahrzeug_id="${fahrzeug_id}">
            ${fahrzeug.status === "aktiv" ? "Deaktivieren" : "Aktivieren"}
          </button>
          <a href="/bearbeiten#routenpunkt-hinzufuegen" class="btn btn-secondary btn-sm" data-fahrzeug_id="${fahrzeug_id}">Route anpassen</a>
          <button class="fahrenStoppenBtn btn btn-secondary btn-sm" data-fahrzeug_id="${fahrzeug_id}">
            ${fahrzeug.status === "aktiv" ? "Stoppen" : "Weiterfahren"}
          </button>
        </td>
      </tr>
    `);
    $tr.after($row);
    offeneButtonZeilen[fahrzeug_id] = $row;
  });

  $('#applyFilterBtnDesktop').on('click', function() {
    filterFahrzeuge('desktop');
  });
  $('#applyFilterBtnMobile').on('click', function() {
    filterFahrzeuge('mobile');
  });

  function filterFahrzeuge(view) {
      const prefix = view === 'desktop' ? '-desktop' : '-mobile';// Desktop- oder Mobilefilter für Tabelle oder Cards
      const fID   = $(`#filter-fahrzeug_id${prefix}`).val()?.trim().toLowerCase() || '';//inputs aus dem Filter
      const stat  = $(`#filter-status${prefix}`).val();
      const time  = $(`#filter-zeitstempel${prefix}`).val()?.trim().toLowerCase() || '';
      const lds   = $(`#filter-ladestand${prefix}`).val();
      const akku  = $(`#filter-akkuzustand${prefix}`).val();
      const ta    = $(`#filter-akt_ta${prefix}`).val()?.trim().toLowerCase() || '';
      /*
      let zahlen = [1, 2, 3, 4, 5];
      let nurGroesserAls3 = zahlen.filter(x => x > 3);
      Ergebnis: [4, 5]
      */
      let filtered = fahrzeugArray.filter(f => {
        if (fID   && !(f.fahrzeug_id || '').toLowerCase().includes(fID)) return false;
        if (stat && (f.status || '').toLowerCase().trim() !== stat.toLowerCase().trim()) return false;
        if (time  && !(f.zeitstempel || '').toLowerCase().includes(time)) return false;
        const ladestandNum = parseInt((f.ladestand || '').replace('%','').trim(), 10);
        const akkuNum      = parseInt((f.akkuzustand || '').replace('%','').trim(), 10);
        if (lds === 'lt50'  && ladestandNum >= 50) return false;// <option value="lt50">&lt;50 %</option>
        if (lds === 'gte50' && ladestandNum <  50) return false;// <option value="gte50">&ge;50 %</option>
        if (akku === 'lt50'  && akkuNum >= 50) return false;
        if (akku === 'gte50' && akkuNum <  50) return false;
        if (ta && !(f.akt_ta || '').toLowerCase().includes(ta)) return false;
        return true;
      });
      if (lastSortCol) sortiereFahrzeugArray(filtered, lastSortCol, lastSortOrder);
      buildFahrzeugTable(filtered);
      buildFahrzeugCards(filtered);
      $('#resetFilterContainer').toggle(filtered.length !== fahrzeugArray.length);
  }

  // Reset-Button beide Filter leeren!
  $('#resetFilterBtn').on('click', function () {
    // Desktop
    $('#filter-fahrzeug_id-desktop, #filter-status-desktop, #filter-zeitstempel-desktop, #filter-ladestand-desktop, #filter-akkuzustand-desktop, #filter-akt_ta-desktop').val('');
    // Mobile
    $('#filter-fahrzeug_id-mobile, #filter-status-mobile, #filter-zeitstempel-mobile, #filter-ladestand-mobile, #filter-akkuzustand-mobile, #filter-akt_ta-mobile').val('');
    let arr = fahrzeugArray.slice();
    if (lastSortCol) sortiereFahrzeugArray(arr, lastSortCol, lastSortOrder);
    buildFahrzeugTable(arr);
    buildFahrzeugCards(arr);
    $('#resetFilterContainer').hide();
  });

  $(document).on('click', '.umschaltenBtn', function() {
    var fahrzeug_id = $(this).data('fahrzeug_id');
    $.post('/fahrzeug/' + fahrzeug_id + '/umschalten', {
      _token: $('meta[name="csrf-token"]').attr('content')
    }, function(response) {
      var idx = fahrzeugArray.findIndex(f => f.fahrzeug_id == fahrzeug_id);
      if (idx !== -1) {
        fahrzeugArray[idx].status = (fahrzeugArray[idx].status == "aktiv") ? "inaktiv" : "aktiv";
        let arr = fahrzeugArray.slice();//flache Kopie des kompletten Arrays (d. h. alle Elemente werden übernommen, aber keine Verweise auf das Original-Array bleiben erhalten).
        if (lastSortCol) sortiereFahrzeugArray(arr, lastSortCol, lastSortOrder);
        buildFahrzeugTable(arr);
        buildFahrzeugCards(arr);
      }
    });
  });

  const fahrzeugTimer = {};

  $(document).on('click', '.fahrenStoppenBtn', function () {
    const fahrzeug_id = $(this).data('fahrzeug_id');
    const idx = fahrzeugArray.findIndex(f => f.fahrzeug_id === fahrzeug_id);
    if (idx === -1) return;
    if (fahrzeugTimer[fahrzeug_id]) {
      clearInterval(fahrzeugTimer[fahrzeug_id].intervalId);
      delete fahrzeugTimer[fahrzeug_id];
      persistiereLadestand(fahrzeugArray[idx]);
      let arr = fahrzeugArray.slice();
      if (lastSortCol) sortiereFahrzeugArray(arr, lastSortCol, lastSortOrder);
      buildFahrzeugTable(arr);
      buildFahrzeugCards(arr);
      return;
    }
    fahrzeugTimer[fahrzeug_id] = {
      aktuellerLadestand: parseInt(fahrzeugArray[idx].ladestand, 10),
      intervalId: setInterval(() => verringereLadestand(fahrzeug_id), 5_000)
    };
  });

  function verringereLadestand(fahrzeug_id) {
    const idx = fahrzeugArray.findIndex(f => f.fahrzeug_id === fahrzeug_id);
    if (idx === -1) return;
    let ladestand = --fahrzeugTimer[fahrzeug_id].aktuellerLadestand;
    fahrzeugArray[idx].ladestand = ladestand + '%';
    let arr = fahrzeugArray.slice();
    if (lastSortCol) sortiereFahrzeugArray(arr, lastSortCol, lastSortOrder);
    buildFahrzeugTable(arr);
    buildFahrzeugCards(arr);
    if (ladestand <= 0) {
      clearInterval(fahrzeugTimer[fahrzeug_id].intervalId);
      delete fahrzeugTimer[fahrzeug_id];
      fahrzeugArray[idx].status  = 'außer Betrieb';
      fahrzeugArray[idx].meldung = 'Akku leer!';
      persistiereLadestand(fahrzeugArray[idx]);
      let arr2 = fahrzeugArray.slice();
      if (lastSortCol) sortiereFahrzeugArray(arr2, lastSortCol, lastSortOrder);
      buildFahrzeugTable(arr2);
      buildFahrzeugCards(arr2);
    }
  }

  function persistiereLadestand(fzg) {
    $.post(
      '/fahrzeuge/' + fzg.fahrzeug_id + '/updateLadestand',
      {
        _token   : $('meta[name="csrf-token"]').attr('content'),
        ladestand: fzg.ladestand,
        status   : fzg.status,
        meldung  : fzg.meldung
      }
    );
  }
</script>
</div>
@endsection
