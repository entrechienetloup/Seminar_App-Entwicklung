@extends('layouts.app')

@section('title', 'Startseite')

@section('content')
<div class="container-fluid py-0">
  <div class="row gx-0 gx-lg-1 gy-3">

    {{-- MAP --}}
    <div id="map-section" class="col-12 col-lg-9 d-flex flex-column">
           <div class="bg-white shadow-sm rounded h-100">
               @include('partials.map') 
           </div>
      </div>

  <div id="stats-section" class="col-12 col-lg-3 shadow bg-white rounded">
  <div class="p-0 bg-white h-100 d-flex flex-column">

    <!-- Tabs Navigation -->
      <ul class="nav nav-tabs mb-2 small " id="statsTabs" role="tablist">
    <li class="nav-item flex-fill" role="presentation">
     <button class="nav-link active w-100" id="fahrzeuge-tab" data-bs-toggle="tab" data-bs-target="#fahrzeuge" type="button" role="tab">Fahrzeuge</button>
    </li>
  </ul>

    <!-- Tabs Content -->
    <div class="tab-content flex-grow-1 overflow-auto" id="statsTabsContent">

      <!-- Fahrzeuge Tab -->
      <div class="tab-pane fade show active" id="fahrzeuge" role="tabpanel">
        <div class="overflow-auto" style="max-height: 72vh;">
          @include('partials.fahrzeuge', ['compact' => true])
        </div>
      </div>

    </div>

  </div>
</div>

  
@endsection