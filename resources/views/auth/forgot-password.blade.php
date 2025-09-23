<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Forgot Password</title>
    @vite('resources/css/app.css')
</head>

<body class="bg-gray-100 flex items-center justify-center min-h-screen">
    <a href="{{ url('/login') }}"
        class="absolute top-6 right-6 bg-yellow-500 text-white px-4 py-2 rounded-md shadow 
          hover:bg-yellow-600 active:scale-95 transition transform duration-150 z-50">
        Back to login page →
    </a>

    <div class="w-full max-w-md bg-white shadow-lg rounded-lg p-6">
        <h2 class="text-2xl font-bold text-center text-amber-500 mb-4">
            Forgot Password
        </h2>
        <p class="text-sm text-gray-600 mb-6 text-center">
            Enter your email and we’ll send you a password reset link.
        </p>

        <!-- Session Status -->
        @if (session('status'))
            <div class="mb-4 text-green-600 font-medium">
                {{ session('status') }}
            </div>
        @endif

        <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
            @csrf

            <!-- Email Address -->
            <div>
                <label for="email" class="block text-amber-500 font-medium">Email</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                    class="mt-1 block w-full rounded-md border border-amber-500 px-3 py-2 focus:ring-amber-500 focus:border-amber-500" />

                @error('email')
                    <p class="mt-2 text-sm text-green-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Button -->
            <div>
                <button type="submit"
                    class="w-full px-4 py-2 rounded-md bg-amber-500 text-white font-semibold shadow hover:bg-amber-600 focus:outline-none focus:ring-2 focus:ring-green-600 focus:ring-offset-2 transition">
                    Send Reset Link
                </button>
            </div>
        </form>
    </div>

</body>

</html>
