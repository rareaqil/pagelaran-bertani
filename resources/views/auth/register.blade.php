<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi - Pagelaran Bertani</title>
    @vite('resources/css/app.css')
</head>

<div class="antialiased">
    <div class="min-h-screen flex flex-col md:flex-row">
        <!-- Left Side (Image + Text) -->
        <div class="hidden lg:flex w-1/2 relative bg-green-700">
            <img src="https://plus.unsplash.com/premium_photo-1675040830254-1d5148d9d0dc?q=80&w=927&auto=format&fit=crop&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D"
                alt="Melon Background" class="absolute inset-0 w-full h-full object-cover" />
            <div class="absolute inset-0 bg-black/30"></div>

            <div class="relative z-10 flex flex-col justify-between p-8">
                <div>
                    <a href="{{ url('/') }}"
                        class="inline-flex items-center bg-yellow-500 text-white px-4 py-2 bg-opacity-60 rounded-md shadow hover:bg-yellow-600 active:scale-95 transition transform duration-150 z-50">
                        ← Back to website
                    </a>
                </div>
                <p class="text-white text-2xl font-semibold max-w-lg text-center px-4">
                    Melon kami lebih unggul dalam rasa, bentuk, dan kualitas dibandingkan yang ada di pasaran
                </p>
            </div>
        </div>

        <!-- Right Side (Register Form) -->
        <div class="flex w-full lg:w-1/2 justify-center items-center bg-green-600">
            <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-2">Create An Account</h2>
                <p class="text-sm text-gray-600 mb-6">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-orange-500 hover:underline">Log in</a>
                </p>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <!-- Name -->
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <input id="first_name" name="first_name" type="text" placeholder="First name"
                                class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-orange-500 focus:outline-none"
                                required autofocus />
                        </div>
                        <div>
                            <input id="last_name" name="last_name" type="text" placeholder="Last name"
                                class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-orange-500 focus:outline-none" />
                        </div>
                    </div>

                    <!-- Email -->
                    <div class="mt-4">
                        <input id="email" type="email" name="email" placeholder="Email"
                            class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-orange-500 focus:outline-none"
                            required />
                    </div>

                    <!-- Password -->
                    <div class="mt-4">
                        <input id="password" type="password" name="password" placeholder="Enter your password"
                            class="w-full px-3 py-2 border rounded focus:ring-2 focus:ring-orange-500 focus:outline-none"
                            required autocomplete="new-password" />
                    </div>

                    <!-- Terms -->
                    <div class="mt-4 flex items-center">
                        <input id="terms" type="checkbox" name="terms" required
                            class="h-4 w-4 text-orange-500 border-gray-300 rounded" />
                        <label for="terms" class="ml-2 text-sm text-gray-600">
                            I agree to the
                            <a href="#" class="text-orange-500 hover:underline">Terms & Conditions</a>
                        </label>
                    </div>

                    <!-- Submit -->
                    <div class="mt-6">
                        <button type="submit"
                            class="w-full bg-orange-500 text-white py-2 rounded hover:bg-orange-600 transition">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
