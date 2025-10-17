<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">

    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
        class="absolute top-6 right-6 bg-red-500 text-white px-4 py-2 rounded-md shadow
        hover:bg-red-600 active:scale-95 transition transform duration-150">
        Logout →
    </a>
    <form id="logout-form" method="POST" action="{{ route('logout') }}" class="hidden">
        @csrf
    </form>

    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6 text-center">
        <h2 class="text-2xl font-bold text-amber-500 mb-4">
            Verify Your Email
        </h2>
        <p class="text-sm text-gray-600 mb-6">
            We've sent a verification link to your email. Please check your inbox.
            <br>
            If you didn't receive it, click the button below.
        </p>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-green-600 font-medium">
                A new verification link has been sent!
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}" class="mb-4">
            @csrf
            <button type="submit"
                class="w-full px-4 py-2 rounded-md bg-amber-500 text-white font-semibold shadow hover:bg-amber-600
                focus:outline-none focus:ring-2 focus:ring-amber-500 focus:ring-offset-2 transition">
                Resend Verification Email
            </button>
        </form>
    </div>

</body>

</html>
