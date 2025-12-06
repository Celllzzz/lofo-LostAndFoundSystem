<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-orange-200 shadow-sm sticky top-0 z-50 transition-all duration-300">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <div class="shrink-0 flex items-center">
                    <a href="{{ auth()->user()->role === 'security' ? route('admin.dashboard') : route('dashboard') }}" class="flex items-center group">
                        <div class="bg-orange-50 p-2 rounded-full group-hover:bg-orange-100 transition duration-300">
                            <svg class="w-6 h-6 text-sky-500 group-hover:scale-110 transition duration-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v.01M10 13a3 3 0 003-3"></path>
                            </svg>
                        </div>
                        <span class="ml-2 text-xl font-bold text-slate-700 tracking-tight group-hover:text-slate-900 transition">Lo<span class="text-sky-500">Fo</span></span>
                    </a>
                </div>

                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    
                    @if(auth()->user()->role === 'security')
                        {{-- MENU KHUSUS SECURITY --}}
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                            {{ __('Dashboard Petugas') }}
                        </x-nav-link>

                    @else
                        {{-- MENU MAHASISWA & ADMIN --}}
                        <x-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
                            {{ __('Beranda') }}
                        </x-nav-link>
                        <x-nav-link :href="route('items.create')" :active="request()->routeIs('items.create')">
                            {{ __('Lapor Barang') }}
                        </x-nav-link>
                        <x-nav-link :href="route('claims.index')" :active="request()->routeIs('claims.index')">
                            {{ __('Klaim Saya') }}
                        </x-nav-link>
                        
                        {{-- Admin tetap bisa lihat dashboard petugas jika perlu --}}
                        @if(auth()->user()->role === 'admin')
                            <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                                {{ __('Dashboard Admin') }}
                            </x-nav-link>
                        @endif
                    @endif

                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-orange-100 text-sm leading-4 font-medium rounded-full text-slate-600 bg-white hover:text-sky-600 hover:bg-orange-50 focus:outline-none transition ease-in-out duration-300 shadow-sm hover:shadow-md">
                            <div class="flex items-center">
                                <span class="mr-2 text-xs font-bold px-2 py-0.5 rounded bg-slate-100 text-slate-500 uppercase">{{ Auth::user()->role }}</span>
                                {{ Auth::user()->name }}
                            </div>
                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            {{ __('Profile') }}
                        </x-dropdown-link>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();">
                                {{ __('Log Out') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-slate-400 hover:text-sky-500 hover:bg-orange-50 focus:outline-none transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-white border-t border-orange-100">
        <div class="pt-2 pb-3 space-y-1">
            @if(auth()->user()->role === 'security')
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.dashboard')">
                    {{ __('Dashboard Petugas') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link :href="route('items.index')" :active="request()->routeIs('items.index')">
                    {{ __('Beranda') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('items.create')" :active="request()->routeIs('items.create')">
                    {{ __('Lapor Barang') }}
                </x-responsive-nav-link>
                <x-responsive-nav-link :href="route('claims.index')" :active="request()->routeIs('claims.index')">
                    {{ __('Klaim Saya') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-orange-100 bg-orange-50/50">
            <div class="px-4">
                <div class="font-medium text-base text-slate-800">{{ Auth::user()->name }}</div>
                <div class="font-medium text-sm text-slate-500">{{ Auth::user()->email }}</div>
            </div>
            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>