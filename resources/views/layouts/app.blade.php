<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/style.css') }}">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    @stack('leaflet-css')
    @stack('styles')
</head>
<body class="d-flex min-vh-100">
    <!-- Sidebar -->
    @include('partials.navbar')

    <!-- Content -->
<div class="flex-grow-1 ">
    <div class="flex-grow-1">
    {{-- Top Bar --}}
    @include('partials.top-bar')
    
    <main class="p-4 pb-5">

        {{-- Main Page Content --}}
        @yield('content')

    </main>
</div>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    @stack('leaflet-js')
</body>
</html>