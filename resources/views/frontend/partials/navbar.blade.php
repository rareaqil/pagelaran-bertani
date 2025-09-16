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
            <li>
                <a href="/login"
                    class="flex items-center space-x-1 {{ request()->is('login') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                    <i class="fa fa-user"></i><span>Login</span>
                </a>
            </li>
            <li>
                <a href="/cart"
                    class="flex items-center space-x-1 {{ request()->is('cart') ? 'text-white underline' : 'text-amber-500 hover:text-white hover:underline transition duration-200' }}">
                    <i class="fa fa-shopping-basket"></i>
                </a>
            </li>
        </ul>
    </div>
</nav>
