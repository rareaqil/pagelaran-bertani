<!DOCTYPE html>
<html lang="en">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagelaran Bertani</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-white text-gray-800">
    {{-- Navbar --}}
    @include('frontend.partials.navbar')

    {{-- Main Content --}}
    <main>
        @yield('content')
    </main>
    @include('frontend.partials.footer')
</body>

</html>
