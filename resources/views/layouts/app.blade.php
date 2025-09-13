<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1" />
        <meta name="csrf-token" content="{{ csrf_token() }}" />

        <title>{{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net" />
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Vite -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        {{-- Loader CSS --}}
        <style>
            /* Loader overlay */
            #loader {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.9);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 9999;
            }

            /* Loader container */
            .loader {
                position: relative;
                width: 150px;
                height: 150px;
            }

            /* Keranjang */
            .basket {
                position: absolute;
                left: 50%;
                bottom: 40%; /* bottom relatif ke loader container */
                transform: translateX(-50%) rotate(0deg);
                width: 120px;
                height: 100px;
                animation: sway 1s infinite ease-in-out alternate;
            }

            .basket svg {
                width: 100%;
                height: 100%;
            }

            @keyframes sway {
                0% {
                    transform: translateX(-50%) rotate(-5deg);
                }
                50% {
                    transform: translateX(-50%) rotate(5deg);
                }
                100% {
                    transform: translateX(-50%) rotate(-5deg);
                }
            }

            /* Buah */
            .fruit {
                position: absolute;
                width: 20px;
                height: 20px;
                border-radius: 50%;
                left: 50%;
                bottom: 59%; /* mulai sedikit di atas keranjang */
                transform: translateX(-50%);
                animation: drop 1.2s infinite ease-in;
            }

            .fruit:nth-child(1) {
                background: #ffa726;
                animation-delay: 0s;
                left: 48%;
            }
            .fruit:nth-child(2) {
                background: #ffa726;
                animation-delay: 0.3s;
                left: 50%;
            }
            .fruit:nth-child(3) {
                background: #ffa726;
                animation-delay: 0.6s;
                left: 52%;
            }

            @keyframes drop {
                0% {
                    transform: translateX(-50%) translateY(0) scale(1);
                    opacity: 0;
                }
                20% {
                    opacity: 1;
                }
                50% {
                    transform: translateX(-50%) translateY(120px) scale(1.2); /* jatuh lebih rendah dan sedikit membesar */
                }
                70% {
                    transform: translateX(-50%) translateY(100px) scale(0.9); /* memantul ke atas */
                }
                85% {
                    transform: translateX(-50%) translateY(110px) scale(1.05); /* pantulan kedua */
                }
                100% {
                    transform: translateX(-50%) translateY(105px) scale(1); /* settle di keranjang */
                }
            }
        </style>

        @stack('styles')

        {{-- untuk tambahan CSS seperti Select2 via CDN jika diperlukan --}}
    </head>
    <body class="font-sans antialiased">
        <!-- Loader -->
        <div id="loader" class="loader">
            <!-- Buah -->
            <div class="fruit"></div>
            <div class="fruit"></div>
            <div class="fruit"></div>

            <!-- Keranjang SVG -->
            <div class="basket">
                <?xml version="1.0" encoding="utf-8"?>
                <svg
                    version="1.1"
                    id="Layer_1"
                    xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink"
                    x="0px"
                    y="0px"
                    viewBox="0 0 122.88 94.27"
                    style="enable-background: new 0 0 122.88 94.27"
                    xml:space="preserve"
                >
                    <style type="text/css">
                        .st0 {
                            fill-rule: evenodd;
                            clip-rule: evenodd;
                            fill: #43a047;
                        }
                    </style>
                    <g>
                        <path
                            class="st0"
                            d="M12.04,27.72h9.43L44.56,1.86c2.05-2.3,5.61-2.5,7.9-0.45v0c2.3,2.05,2.5,5.61,0.45,7.91l-16.42,18.4h50.32
        L70.39,9.32c-2.05-2.3-1.85-5.86,0.45-7.91h0c2.3,2.05,5.85-1.85,7.91,0.45l23.08,25.86l9.02,0l0.12,0l8.47,0
        c1.9,0,3.45,1.55,3.45,3.45v9.73c0,1.9-1.55,3.45-3.45,3.45h-7.33l-3.77,47.53c-0.1,1.31-1.08,2.39-2.39,2.39H16.94
        c-1.31,0-2.29-1.08-2.39-2.39l-3.77-47.53H3.45C1.55,44.35,0,42.8,0,40.9v-9.73c0-1.9,1.55-3.45,3.45-3.45l8.47,0L12.04,27.72
        L12.04,27.72z M77.67,46.22h10.91v31.53l-10.91,0V46.22L77.67,46.22z M56.45,46.22h10.9v31.53l-10.9,0V46.22L56.45,46.22z
        M35.23,46.22h10.91v31.53l-10.91,0V46.22L35.23,46.22z"
                        />
                    </g>
                </svg>
            </div>
        </div>
        <div class="min-h-screen bg-gray-100">
            @include('layouts.navigation')

            <!-- Page Heading -->
            @isset($header)
                <header class="bg-white shadow">
                    <div class="mx-auto max-w-7xl px-4 py-6 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>
        </div>
    </body>

    {{-- Loader styles --}}

    {{-- Loader script --}}
    <script>
        window.addEventListener('load', function () {
            const loader = document.getElementById('loader');
            loader.style.transition = 'opacity 0.5s';
            loader.style.opacity = 0;
            setTimeout(() => (loader.style.display = 'none'), 500);
        });
    </script>

    <!-- Jangan lupa load Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css" />
    {{-- Stack untuk script tambahan --}}
    @stack('scripts')
</html>
