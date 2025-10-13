<nav x-data="{ open: false }" class="bg-green-600 text-white shadow-md">
    <div class="container mx-auto flex items-center justify-between py-4 px-6">
        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center space-x-2">
            <img src="{{ asset('logo.png') }}" alt="Logo" class="h-10 w-10">
            <span class="font-bold text-lg md:text-xl">PAGELARAN BERTANI</span>
        </a>

        <!-- Hamburger Menu (Mobile) -->
        <button @click="open = !open" class="md:hidden text-amber-400 focus:outline-none">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path x-show="!open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M4 6h16M4 12h16M4 18h16" />
                <path x-show="open" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Menu Desktop -->
        <ul class="hidden md:flex items-center space-x-6">
            <li>
                <a href="/"
                    class="{{ request()->is('/') ? 'text-white underline' : 'text-amber-400 hover:text-white hover:underline transition' }}">
                    Home
                </a>
            </li>
            <li>
                <a href="/learn"
                    class="{{ request()->is('learn') ? 'text-white underline' : 'text-amber-400 hover:text-white hover:underline transition' }}">
                    Learn
                </a>
            </li>
            <li>
                <a href="/order-product"
                    class="{{ request()->is('order-product') ? 'text-white underline' : 'text-amber-400 hover:text-white hover:underline transition' }}">
                    Order Product
                </a>
            </li>
            <li>
                <a href="/contact-us"
                    class="{{ request()->is('contact-us') ? 'text-white underline' : 'text-amber-400 hover:text-white hover:underline transition' }}">
                    Contact Us
                </a>
            </li>
            <li>
                <a href="/order-history"
                    class="{{ request()->is('order-history') ? 'text-white underline' : 'text-amber-400 hover:text-white hover:underline transition' }}">
                    Order History
                </a>
            </li>

            {{-- User Dropdown --}}
            <li class="relative" x-data="{ dropdown: false }">
                @guest
                    <a href="{{ route('login') }}"
                        class="flex items-center space-x-1 {{ request()->is('login') ? 'text-white underline' : 'text-amber-400 hover:text-white hover:underline transition' }}">
                        <i class="fa fa-user"></i><span>Login</span>
                    </a>
                @else
                    <button @click="dropdown = !dropdown"
                        class="flex items-center space-x-1 text-amber-400 hover:text-white hover:underline transition focus:outline-none">
                        <i class="fa fa-user"></i>
                        <span>{{ auth()->user()->first_name }} {{ auth()->user()->last_name }}</span>
                        <i class="fa fa-caret-down ml-1"></i>
                    </button>

                    <ul x-cloak x-show="dropdown" @click.away="dropdown = false" x-transition
                        class="absolute right-0 mt-2 w-40 bg-white text-gray-700 shadow-lg rounded-lg py-2 z-50">
                        <li>
                            <a href="{{ route('dashboard') }}" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="fa fa-tachometer-alt mr-2"></i> Dashboard
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100">
                                <i class="fa fa-user-circle mr-2"></i> Profile
                            </a>
                        </li>
                        <li>
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2 hover:bg-gray-100">
                                    <i class="fa fa-sign-out-alt mr-2"></i> Logout
                                </button>
                            </form>
                        </li>
                    </ul>
                @endguest
            </li>

            {{-- Cart --}}
            <li class="relative">
                <a href="{{ route('cart.show') }}"
                    class="flex items-center space-x-1 {{ request()->is('cart') ? 'text-white underline' : 'text-amber-400 hover:text-white hover:underline transition' }}">
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

    <!-- Menu Mobile -->
    <div x-show="open" x-transition
        class="md:hidden bg-green-700 px-6 py-4 space-y-3 border-t border-green-500 text-amber-200">
        <a href="/"
            class="block hover:text-white {{ request()->is('/') ? 'underline text-white' : '' }}">Home</a>
        <a href="/learn"
            class="block hover:text-white {{ request()->is('learn') ? 'underline text-white' : '' }}">Learn</a>
        <a href="/order-product"
            class="block hover:text-white {{ request()->is('order-product') ? 'underline text-white' : '' }}">Order
            Product</a>
        <a href="/contact-us"
            class="block hover:text-white {{ request()->is('contact-us') ? 'underline text-white' : '' }}">Contact
            Us</a>
        <a href="/order-history"
            class="block hover:text-white {{ request()->is('order-history') ? 'underline text-white' : '' }}">Order
            History</a>

        @guest
            <a href="{{ route('login') }}"
                class="block hover:text-white {{ request()->is('login') ? 'underline text-white' : '' }}">Login</a>
        @else
            <div class="border-t border-green-500 pt-3">
                <p class="font-semibold mb-2">{{ auth()->user()->first_name }}</p>
                @auth
                    @if (auth()->user()->role !== 'user')
                        <a href="{{ route('dashboard') }}"
                            class="block hover:text-white {{ request()->is('backend/dashboard') ? 'underline text-white' : '' }}">
                            Dashboard
                        </a>
                    @endif
                @endauth
                <a href="{{ route('profile.edit') }}" class="block hover:text-white mt-1">Profile</a>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left hover:text-white mt-1">Logout</button>
                </form>
            </div>
        @endguest

        <div class="border-t border-green-500 pt-3">
            <a href="{{ route('cart.show') }}" class="flex items-center space-x-1 hover:text-white">
                <i class="fa fa-shopping-basket"></i>
                <span>Cart</span>
                @if (!empty($cartCount) && $cartCount > 0)
                    <span
                        class="ml-2 bg-red-600 text-white text-xs font-bold px-2 py-0.5 rounded-full">{{ $cartCount }}</span>
                @endif
            </a>
        </div>
    </div>
</nav>

<script src="//unpkg.com/alpinejs" defer></script>
