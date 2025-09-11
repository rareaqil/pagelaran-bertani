<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Pagelaran Bertani</title>
    @vite('resources/css/app.css')
</head>

<body class="antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left (form) -->
        <div class="w-full md:w-1/2 flex flex-col justify-center bg-green-600 px-8 md:px-16 lg:px-24 py-12">
            <h2 class="text-3xl font-bold text-white mb-6">Welcome to <br> Pagelaran Bertani</h2>

            <form method="POST" action="{{ route('login') }}" class="space-y-4">
                @csrf
                <input id="email" type="email" name="email" placeholder="Email address" required autofocus
                    class="w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-yellow-400" />

                <input id="password" type="password" name="password" placeholder="Password" required
                    class="w-full rounded-md border-0 px-3 py-2 text-gray-900 shadow-sm placeholder-gray-400 focus:ring-2 focus:ring-yellow-400" />

                <div class="text-sm">
                    <a href="{{ route('password.request') }}" class="text-white underline">
                        Forgot password?
                    </a>
                </div>

                <button type="submit"
                    class="w-full bg-yellow-500 text-white py-2 rounded-md font-semibold shadow-md hover:bg-yellow-600 transition">
                    Log In
                </button>

                <p class="mt-4 text-center text-white">
                    Don’t Have An Account?
                    <a href="{{ route('register') }}" class="font-semibold text-yellow-400 hover:underline">
                        Register Now.
                    </a>
                </p>
            </form>
        </div>

        <!-- Right (image + text) -->
        <div class="hidden md:flex md:w-1/2 relative bg-cover bg-center"
            style="background-image: url('https://plus.unsplash.com/premium_photo-1675040830254-1d5148d9d0dc?q=80&w=927&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D);">

            <div class="absolute inset-0 bg-black/20"></div>

            <div class="absolute top-6 left-6 flex items-center space-x-2">
                <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10">
                <span class="text-white font-bold text-lg">PAGELARAN BERTANI</span>
            </div>

            <a href="{{ url('/') }}"
                class="absolute top-6 right-6 bg-yellow-500 text-white px-4 py-2 rounded-md shadow 
          hover:bg-yellow-600 active:scale-95 transition transform duration-150 z-50">
                Back to website →
            </a>

            <div class="absolute inset-0 flex items-center justify-center">
                <p class="text-white text-2xl font-semibold max-w-lg text-center px-4">
                    Melon kami lebih unggul dalam rasa, bentuk, dan kualitas dibandingkan yang ada di pasaran
                </p>
            </div>
        </div>
    </div>
</body>

</html>
