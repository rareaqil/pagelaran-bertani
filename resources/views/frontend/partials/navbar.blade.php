<nav class="bg-green-600 text-white">
    <div class="container mx-auto flex items-center justify-between py-4 px-6">
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10">
            <span class="font-bold text-xl">PAGELARAN BERTANI</span>
        </a>

        <ul class="flex items-center space-x-6">
            <li>
                <a href="/"
                    class="{{ request()->is('/') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="/learn"
                    class="{{ request()->is('learn') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                    Learn
                </a>
            </li>
            <li>
                <a href="/order-product"
                    class="{{ request()->is('order-product') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                    Order Product
                </a>
            </li>
            <li>
                <a href="/contact-us"
                    class="{{ request()->is('contact-us') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                    Contact Us
                </a>
            </li>
            <li class="relative" x-data="{ open: false }">
                @guest
                    {{-- Kalau belum login --}}
                    <a href="{{ route('login') }}"
                        class="flex items-center space-x-1 {{ request()->is('login') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                        <i class="fa fa-user"></i><span>Login</span>
                    </a>
                @else
                    {{-- Kalau sudah login --}}
                    <button @click="open = !open"
                        class="flex items-center space-x-1 text-amber-500 hover:text-white hover:underline transition duration-200 focus:outline-none">
                        <i class="fa fa-user"></i>
                        <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                        <i class="fa fa-caret-down ml-1"></i>
                    </button>

                    {{-- Dropdown --}}
                    <ul x-cloak x-show="open" @click.away="open = false" x-transition
                        class="absolute right-0 mt-2 w-40 bg-white shadow-lg rounded-lg py-2 z-50">
                        <li>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-gray-700 hover:bg-gray-100">
                                <i class="fa fa-user-circle mr-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 text-gray-700 hover:bg-gray-100">
                                    <i class="fa fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                @endguest
            </li>
            <li class="relative">
                <a href="{{ route('cart.show') }}"
                    class="flex items-center space-x-1 {{ request()->is('cart') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                    <i class="fa fa-shopping-basket text-lg"></i>

                    @if (!empty($cartCount) && $cartCount > 0)
                        <span
                            class="absolute -top-2 -right-2 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </li>
        </ul>
    </div>
</nav>
<script src="//unpkg.com/alpinejs" defer></script>
