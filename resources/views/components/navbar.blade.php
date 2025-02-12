<nav class="bg-gray-800" x-data="{ isOpen: false }">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="flex h-16 items-center justify-between">
            <div class="flex items-center">
                <div class="shrink-0">
                    <a href="/">
                        <span class="self-center text-xl font-semibold sm:text-2xl whitespace-nowrap text-white">LibFinder</span>
                    </a>
                </div>
                <div class="hidden md:block">
                    <div class="ml-10 flex items-baseline space-x-4">
                        <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                        <x-nav-link href="/libraries" :active="request()->is('libraries')">Libraries</x-nav-link>
                        <x-nav-link href="/browse" :active="request()->is('browse')">Browse Book</x-nav-link>
                        <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
                    </div>
                </div>
            </div>
            <div class="hidden md:block">
                <div class="ml-4 flex items-center">
                    <div>
                        <p id="province" class="text-sm font-medium text-white">Fetching...</p>
                        <p id="city" class="text-xs text-gray-400">Please wait...</p>
                    </div>
                    <!-- Profile dropdown -->
                    <div class="relative ml-3">
                        <button @click="isOpen = !isOpen" class="flex items-center text-gray-700 dark:text-white focus:outline-none">
                            @if(Auth::check() && Auth::user()->avatar)             
                                <img class="size-8 rounded-full" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
                            @else 
                                @php
                                    $initial = strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)); 
                                @endphp
                                <div class="size-8 flex items-center justify-center rounded-full bg-gray-500 text-white text-lg font-bold">
                                    {{ $initial }}
                                </div>
                            @endif
                        </button>
                        <div x-show="isOpen" x-transition 
                            class="absolute right-0 z-10 mt-2 w-48 origin-top-right rounded-md bg-white py-1 shadow-lg ring-1 ring-black/5 focus:outline-none">
                            @auth            
                                <a href="/profile" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">My Profile</a>
                                <form action="/logout" method="post">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        Sign out
                                    </button>
                                </form>
                            @else
                                <a href="/login" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Login</a>
                                <a href="/register" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Register</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
            <!-- Mobile menu button -->
            <div class="-mr-2 flex md:hidden">
                <button @click="isOpen = !isOpen" class="relative inline-flex items-center justify-center rounded-md bg-gray-800 p-2 text-gray-400 hover:bg-gray-700 hover:text-white focus:outline-none">
                    <svg :class="{'hidden': isOpen, 'block': !isOpen }" class="block size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>
                    <svg :class="{'block': isOpen, 'hidden': !isOpen }" class="hidden size-6" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div x-show="isOpen" class="md:hidden">
        <div class="space-y-1 px-2 pb-3 pt-2 sm:px-3">
            <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
            <x-nav-link href="/libraries" :active="request()->is('libraries')">Libraries</x-nav-link>
            <x-nav-link href="/browse" :active="request()->is('browse')">Browse Book</x-nav-link>
            <x-nav-link href="/about" :active="request()->is('about')">About</x-nav-link>
        </div>
        <!-- Mobile Profile -->
        <div class="border-t border-gray-700 pb-3 pt-4">
            <div class="flex items-center px-5">
                @auth
                    <div class="shrink-0">
                        @if(Auth::user()->avatar)             
                            <img class="size-10 rounded-full" src="{{ asset('storage/' . Auth::user()->avatar) }}" alt="Profile">
                        @else 
                            <div class="size-10 flex items-center justify-center rounded-full bg-gray-500 text-white text-lg font-bold">
                                {{ strtoupper(substr(Auth::user()->name ?? 'U', 0, 1)) }}
                            </div>
                        @endif
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-white">{{ Auth::user()->name }}</div>
                        <div class="text-sm font-medium text-gray-400">{{ Auth::user()->email }}</div>
                    </div>
                @endauth
            </div>
            <div class="mt-3 space-y-1 px-2">
                @auth
                    <a href="/profile" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Your Profile</a>
                    <form action="/logout" method="post">
                        @csrf
                        <button type="submit" class="block w-full text-left px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">
                            Sign out
                        </button>
                    </form>
                @else
                    <a href="/login" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Login</a>
                    <a href="/register" class="block rounded-md px-3 py-2 text-base font-medium text-gray-400 hover:bg-gray-700 hover:text-white">Register</a>
                @endauth
            </div>
        </div>
    </div>
</nav>
