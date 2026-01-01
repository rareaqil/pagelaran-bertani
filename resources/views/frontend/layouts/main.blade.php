<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Pagelaran Bertani</title>

    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />

    {{-- Vite CSS + JS (bukan hanya CSS saja) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- CSRF Token (supaya form & AJAX jalan) --}}
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    {{-- Styles tambahan dari child --}}
    @yield('styles')

    {{-- SweetAlert (opsional) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- Alpine.js (untuk x-data, x-show, dll) --}}
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <!-- JQUERY dulu -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/tom-select/dist/css/tom-select.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/tom-select/dist/js/tom-select.complete.min.js"></script>

</head>

<body class="bg-white text-gray-800">
    {{-- Navbar --}}
    @include('frontend.partials.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    @include('frontend.partials.footer')

    {{-- Stack script tambahan (dari @push) --}}
    @stack('scripts')

    {{-- Yield script biasa --}}
    @yield('scripts')
</body>

</html>
