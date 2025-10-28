@extends('layouts.app')

@section('title', 'Aufträge')

@section('content')

<div class="container py-4">
<!-- ---------- Filter-UI  ---------- -->

  <!-- Mobile filter toggle -->
<div class="d-lg-none mb-2">
  <button class="btn btn-outline-info w-100" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapseMobile">
    <i class="bi bi-funnel-fill"></i> Filter
  </button>
  <div class="collapse mt-2" id="filterCollapseMobile">
    <div class="card card-body">
      <div class="row g-2">
        <!-- Transportauftrag-ID -->
        <div class="col-md-2">
          <input id="filter-ta-mobile" type="text" class="form-control" placeholder="Transportauftrag-ID">
        </div>

        <!-- Status -->
        <div class="col-md-2">
          <select id="filter-status-mobile" class="form-select">
            <option value="">Status</option>
            <option value="beendet">beendet</option>
            <option value="wird ausgeführt">wird&nbsp;ausgeführt</option>
            <option value="wartet">wartet</option>
          </select>
        </div>

        <!-- Startort -->
        <div class="col-md-2">
          <input id="filter-startort_id-mobile" type="text" class="form-control" placeholder="Startort">
        </div>

        <!-- Zielort -->
        <div class="col-md-2">
          <input id="filter-zielort_id-mobile" type="text" class="form-control" placeholder="Zielort">
        </div>

        <!-- Fahrzeug-ID -->
        <div class="col-md-2">
          <input id="filter-fahrzeug-mobile" type="text" class="form-control" placeholder="Fahrzeug-ID">
        </div>

        <div class="col-md-2">
          <button id="applyFilterBtnMobile" class="btn btn-info w-100">Filter anwenden</button>
        </div>
      </div>
    </div>
  </div>
</div>


    <div class="card card-body d-none d-lg-block mb-3">
      <div class="row g-2">

        <!-- Transportauftrag-ID -->
        <div class="col-md-2">
          <input id="filter-ta-desktop" type="text" class="form-control"
                placeholder="Transportauftrag-ID">
        </div>

        <!-- Status -->
        <div class="col-md-2">
          <select id="filter-status-desktop" class="form-select">
            <option value="">Status</option>
            <option value="beendet">beendet</option>
            <option value="wird ausgeführt">wird&nbsp;ausgeführt</option>
            <option value="wartet">wartet</option>
          </select>
        </div>

        <!-- Startort -->
        <div class="col-md-2">
          <input id="filter-startort_id-desktop" type="text" class="form-control"
                placeholder="Startort">
        </div>

        <!-- Zielort -->
        <div class="col-md-2">
          <input id="filter-zielort_id-desktop" type="text" class="form-control"
                placeholder="Zielort">
        </div>

        <!-- Fahrzeug-ID -->
        <div class="col-md-2">
          <input id="filter-fahrzeug-desktop" type="text" class="form-control"
                placeholder="Fahrzeug-ID">
        </div>

        <div class="col-md-2">
          <button id="applyFilterBtnDesktop" class="btn btn-info w-100">
            Filter anwenden
          </button>
        </div>
      </div>
    </div>

<div id="resetFilterContainer" class="mb-2" style="display:none;">
  <button id="resetFilterBtn" class="btn btn-outline-secondary btn-sm">Filter zurücksetzen</button>
</div>

<template id="auftrag-row-template">
  <tr data-id="_transportauftrag_id_" data-status="_status_">
    <td>
     <span class="transportauftrag_id">_transportauftrag_id_</span>
    </td>
    <td>
      <span class="status">_status_</span>
    </td>
    <td>
      <span class="prioritaet">_prioritaet_</span>
    </td>
    <td>
      <span class="noEdit startort_id">_startort_id_</span>
      <input class="edit startort_id form-control" type="text" value="_startort_id_" style="display:none;">
    </td>
    <td>
      <span class="noEdit zielort_id">_zielort_id_</span>
      <input class="edit zielort_id form-control" type="text" value="_zielort_id_" style="display:none;">
    </td>
    <td>
      <span class="noEdit fahrzeug_id">_fahrzeug_id_</span>
      <input class="edit fahrzeug_id form-control" type="text" value="_fahrzeug_id_" style="display:none;">
    </td>
    <td>
      <button type="button" class="btn btn-light btn-sm toggleActionsBtn">Ausklappen</button>
    </td>
  </tr>
</template>

<style>
  th{ 
    cursor: pointer;
    color:#fff;
  }
</style>

<table id="auftragsTable" class="table table-striped d-none d-lg-table">
  <thead>
    <tr class="bg-info">
      <th data-colname="transportauftrag_id" data-order="desc">Transportauftrag-ID
 &#9650</th>
      <th data-colname="status" data-order="desc">Status &#9650</th>
      <th data-colname="prioritaet" data-order="desc">Priorität &#9650</th>
      <th data-colname="startort_id" data-order="desc">Startort &#9650</th>
      <th data-colname="zielort_id" data-order="desc">Zielort &#9650</th>
      <th data-colname="fahrzeug_id" data-order="desc">Fahrzeug-ID &#9650</th>
      <th>Aktion</th>
    </tr>
  </thead>
  <tbody id="auftragsTableBody"></tbody>
</table>

<div id="auftragCardContainer" class="d-lg-none"></div>

<script>
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });
  var auftragArray = @json($auftraege);
  let offeneButtonZeilen = {};
  let lastSortCol = null;
  let lastSortOrder = null;

  function htmlEncode(str) {
    return String(str ?? '').replace(/&/g, '&amp;')
      .replace(/"/g, '&quot;')
      .replace(/'/g, '&#39;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;');
  }

  function parseTaId(id) {
    return Number((id||'').replace(/\D/g,''));
  }

  function buildAuftragTable(data) {
    $('.action-row').remove();
    offeneButtonZeilen = {};
    var table = document.getElementById('auftragsTableBody');
    var tpl = document.getElementById('auftrag-row-template').innerHTML;
    table.innerHTML = '';
    data.forEach(function(row) {
      var tr = tpl
        .replace(/_transportauftrag_id_/g, htmlEncode(row.transportauftrag_id))
        .replace(/_status_/g, htmlEncode(row.status))
        .replace(/_prioritaet_/g, htmlEncode(row.prioritaet || ''))
        .replace(/_startort_id_/g, htmlEncode(row.startort_id))
        .replace(/_zielort_id_/g, htmlEncode(row.zielort_id))
        .replace(/_fahrzeug_id_/g, htmlEncode(row.fahrzeug_id));
      table.innerHTML += tr;
    });
  }

  function buildAuftragCards(data) {
    const container = document.getElementById('auftragCardContainer');
    container.innerHTML = '';

    data.forEach(a => {
      const id = htmlEncode(a.transportauftrag_id);
      const isBeendet = a.status === 'beendet';
      const editDisabled = isBeendet ? 'disabled' : '';

      const card = document.createElement('div');
      card.className = 'card mb-3 shadow-sm';
      card.setAttribute('data-id', id);
      card.innerHTML = `
        <div class="card-body">
          <h6 class="card-title mb-2 fw-bold"><i class="bi bi-box-seam me-1"></i> ${id}</h6>

          <div class="card-view">
            <p class="mb-1"><strong>Status:</strong> ${htmlEncode(a.status)}</p>
            <p class="mb-1"><strong>Priorität:</strong> ${htmlEncode(a.prioritaet || '')}</p>
            <p class="mb-1"><strong>Startort:</strong> ${htmlEncode(a.startort_id)}</p>
            <p class="mb-1"><strong>Zielort:</strong> ${htmlEncode(a.zielort_id)}</p>
            <p class="mb-3"><strong>Fahrzeug-ID:</strong> ${htmlEncode(a.fahrzeug_id)}</p>
          </div>

          <div class="card-edit d-none">
            <input type="text" class="form-control mb-1 edit startort_id" value="${htmlEncode(a.startort_id)}" placeholder="Startort">
            <input type="text" class="form-control mb-1 edit zielort_id" value="${htmlEncode(a.zielort_id)}" placeholder="Zielort">
            <input type="text" class="form-control mb-2 edit fahrzeug_id" value="${htmlEncode(a.fahrzeug_id)}" placeholder="Fahrzeug-ID">
          </div>

          <div class="d-flex flex-wrap gap-2 justify-content-end">
            ${!isBeendet ? `<button class="btn btn-sm btn-secondary priorisierenBtn" data-id="${id}">Priorisieren</button>` : ''}
            <button class="btn btn-sm btn-secondary anzeigenBtn" data-id="${id}">Anzeigen</button>
          </div>
        </div>
      `;
      container.appendChild(card);
    });
  }

  function sortiereAuftragArray(arr, col, order) {
    arr.sort(function(a, b) {
      let va = a[col] ?? '', vb = b[col] ?? '';
      if (col === 'transportauftrag_id' || col === 'prioritaet') {
        return order === 'asc'
          ? (parseFloat(va)||0) - (parseFloat(vb)||0)
          : (parseFloat(vb)||0) - (parseFloat(va)||0);
      }
      va = va.toString().toLowerCase();
      vb = vb.toString().toLowerCase();
      if (va < vb) return order === 'asc' ? -1 : 1;
      if (va > vb) return order === 'asc' ? 1 : -1;
      return 0;
    });
  }

  $(document).on('click', '.toggleActionsBtn', function() {
    const $btn   = $(this);
    const $tr    = $btn.closest('tr');
    const id     = $tr.data('id');
    const status = $tr.data('status');   // hier holen wir den Status

    // Aktion-Row wieder einklappen, falls offen
    if (offeneButtonZeilen[id]) {
      offeneButtonZeilen[id].remove();
      delete offeneButtonZeilen[id];
      $btn.text('Ausklappen');
      return;
    }
    $btn.text('Einklappen');

    // Nur wenn nicht schon 'beendet'
    const priorisierenBtn = status !== 'beendet'
      ? `<button class="noEdit priorisierenBtn btn btn-secondary btn-sm" data-id="${id}">Priorisieren</button>`
      : '';
    const $actionRow = $(`
      <tr class="action-row" data-id="${id}">
        <td colspan="7" class="text-end" style="background:#f6f6f6;">
          <span style="font-weight:bold;">${id}</span>
          ${priorisierenBtn}
          <button class="noEdit deleteBtn btn btn-secondary btn-sm" data-id="${id}">Löschen</button>
          <button class="noEdit editBtn btn btn-secondary btn-sm" data-id="${id}">Bearbeiten</button>
          <button class="noEdit anzeigenBtn btn btn-secondary btn-sm" data-id="${id}">Anzeigen</button>
          <form action="{{ route('seed.auftraege') }}" method="GET" style="display:inline;">
            <button type="submit" class="edit saveEdit btn btn-secondary btn-sm">Bestätigen</button>
          </form>
          <button class="edit cancelEdit btn btn-secondary btn-sm" data-id="${id}">Abbrechen</button>
        </td>
      </tr>
    `);

    $tr.after($actionRow);
    offeneButtonZeilen[id] = $actionRow;
  });

  function setupAuftragHandlers() {
    $('#auftragsTable').on('click','.editBtn', function() {
      var id = $(this).data('id');
      var $dataRow = $(`#auftragsTableBody tr[data-id="${id}"]`);
      $dataRow.addClass('editing');// die Zeile in editing Zustand umschalten
      $dataRow.find('.noEdit').hide();// Buttons mit noEdit-Klasse ausblenden (Priorisieren Löschen Bearbeiten Anzeigen)
      $dataRow.find('.edit').show();// Eingabefelder und Buttons (Bestätigen Abbrechen) mit edit-Klasse einblenden
      $dataRow.next('.action-row').find('.editBtn, .toggleActionsBtn').hide();
    });

    $('#auftragsTable').on('click','.cancelEdit', function() {
      var id = $(this).data('id');
      var $dataRow = $(`#auftragsTableBody tr[data-id="${id}"]`);
      $dataRow.removeClass('editing');
      $dataRow.find('.edit').hide();
      $dataRow.find('.noEdit').show();
      $dataRow.next('.action-row').find('.editBtn, .toggleActionsBtn').show();
    });

    $('#auftragsTable').on('click','.saveEdit', function() {
      var id = $(this).data('id');
      var $dataRow = $(`#auftragsTableBody tr[data-id="${id}"]`);
      var updated = {};
      $dataRow.find('input.edit').each(function() {
        var field = $(this).attr('class').split(' ')[1];
        updated[field] = $(this).val();
      });
      $.ajax({
        type: 'PUT',
        url: '/api/auftraege/' + id,
        data: {...updated, _method: 'PUT'},
        success: function(data) {
          var idx = auftragArray.findIndex(item => item.transportauftrag_id == id);
          if (idx !== -1) Object.assign(auftragArray[idx], updated);
          let arr = auftragArray.slice();
          if (lastSortCol) sortiereAuftragArray(arr, lastSortCol, lastSortOrder);
          buildAuftragTable(arr);
          buildAuftragCards(arr);
        },
        error: function() {
         // alert('Fehler beim Speichern!'); Zeitweise für Szenario, sonst kam das Popup
        }
      });
    });

    $('#auftragsTable').on('click','.deleteBtn', function() {
      var id = $(this).data('id');
      if (!confirm('Möchtest du diesen Auftrag wirklich löschen?')) return;
      $.ajax({
        type: 'DELETE',
        url: '/api/auftraege/' + id,
        success: function() {
          auftragArray = auftragArray.filter(item => item.transportauftrag_id != id);
          let arr = auftragArray.slice();// flache Kopie erzeugen
          if (lastSortCol) sortiereAuftragArray(arr, lastSortCol, lastSortOrder);
          buildAuftragTable(arr);
          buildAuftragCards(arr);
        },
        error: function() {
          alert('Fehler beim Löschen!');
        }
      });
    });

    $(document).on('click', '.anzeigenBtn', function() {
      window.location.href = '/dashboard';
    });

    $(document).on('click', '.priorisierenBtn', function() {
      var id = $(this).data('id');
      $.post('/auftraege/' + id + '/priorisieren', {
        _token: $('meta[name="csrf-token"]').attr('content')
      }, function() {
        $.getJSON('/api/auftraege', function(data) {
          auftragArray = data;
          buildAuftragTable(auftragArray);
          buildAuftragCards(auftragArray);
        });
      });
    });
  }

  function setupAuftragSearch() {
    $('#auftrag-search-input').on('keyup', function() {
      var searchTerm = $(this).val().toLowerCase();
      var filteredArray = auftragArray.filter(function(item) {
        return Object.values(item).some(function(val) {
          return val && String(val).toLowerCase().includes(searchTerm);
        });
      });
      if (lastSortCol) sortiereAuftragArray(filteredArray, lastSortCol, lastSortOrder);
      buildAuftragTable(filteredArray);
      buildAuftragCards(filteredArray);
    });
  }

  function updateHeaderSortIcons(activeCol, order) {
    $('#auftragsTable th[data-colname]').each(function() {
      const $this = $(this);
      const col   = $this.data('colname');
      let txt = $this.text().replace(/[\u25B2\u25BC]/g, '').trim();
      if (col === activeCol) {
        $this.html(`${txt} ${order === 'asc' ? '▲' : '▼'}`);
      } else {
        $this.html(txt);
        $this.data('order', 'asc');
      }
    });
  }

  $(function() {
    const statusOrder = {'wird ausgeführt':1, 'wartet':2, 'beendet':3};

    auftragArray.sort((a, b) => {
      // 1) Status
      const sa = statusOrder[a.status] || 99;
      const sb = statusOrder[b.status] || 99;
      if (sa !== sb) return sa - sb;

      // 2) Priorität (numerisch)
      const pa = parseFloat(a.prioritaet) || 0;
      const pb = parseFloat(b.prioritaet) || 0;
      if (pa !== pb) return pa - pb;

      // 3) Fallback: ID
      return parseTaId(a.transportauftrag_id) - parseTaId(b.transportauftrag_id);
    });
    buildAuftragTable(auftragArray);
    buildAuftragCards(auftragArray);
    setupAuftragHandlers();
    setupAuftragSearch();
    $('#auftragsTable').on('click','th[data-colname]', function(){
      const $th = $(this);
      const col = $th.data('colname');
      const cur = $th.data('order');
      const next = cur === 'asc' ? 'desc' : 'asc';
      lastSortCol = col;
      lastSortOrder = next;
      let arr = auftragArray.slice();
      sortiereAuftragArray(arr, col, next);
      updateHeaderSortIcons(col, next);
      buildAuftragTable(arr);
      buildAuftragCards(arr);
      $th.data('order', next);
    });
    updateHeaderSortIcons(null, null);
  });

  $('#applyFilterBtnDesktop').on('click', function() {
    filterAuftraege('desktop');
  });
  $('#applyFilterBtnMobile').on('click', function() {
    filterAuftraege('mobile');
  });

  function filterAuftraege(view) {
      const prefix = view === 'desktop' ? '-desktop' : '-mobile';
      const ta     = $(`#filter-ta${prefix}`).val()?.trim().toLowerCase() || '';
      const status = $(`#filter-status${prefix}`).val();
      const start  = $(`#filter-startort_id${prefix}`).val()?.trim().toLowerCase() || '';
      const ziel   = $(`#filter-zielort_id${prefix}`).val()?.trim().toLowerCase() || '';
      const fzg    = $(`#filter-fahrzeug${prefix}`).val()?.trim().toLowerCase() || '';
      let filtered = auftragArray.filter(a => {
        if (ta     && !(a.transportauftrag_id || '').toLowerCase().includes(ta))   return false;
        if (status && (a.status || '').toLowerCase().trim() !== status.toLowerCase().trim()) return false;
        if (start  && !(a.startort_id || '').toLowerCase().includes(start))        return false;
        if (ziel   && !(a.zielort_id  || '').toLowerCase().includes(ziel))         return false;
        if (fzg    && !(a.fahrzeug_id || '').toLowerCase().includes(fzg))          return false;
        return true;
      });
      if (lastSortCol) sortiereAuftragArray(filtered, lastSortCol, lastSortOrder);
      buildAuftragTable(filtered);
      buildAuftragCards(filtered);
      $('#resetFilterContainer').toggle(filtered.length !== auftragArray.length);
  }

  // Reset-Button beide Filter leeren!
  $('#resetFilterBtn').on('click', function () {
    // Desktop
    $('#filter-ta-desktop, #filter-status-desktop, #filter-startort_id-desktop, #filter-zielort_id-desktop, #filter-fahrzeug-desktop').val('');
    // Mobile
    $('#filter-ta-mobile, #filter-status-mobile, #filter-startort_id-mobile, #filter-zielort_id-mobile, #filter-fahrzeug-mobile').val('');
    let arr = auftragArray.slice();
    if (lastSortCol) sortiereAuftragArray(arr, lastSortCol, lastSortOrder);
    buildAuftragTable(arr);
    buildAuftragCards(arr);
    $('#resetFilterContainer').hide();
  });
</script>
</div>
@endsection
