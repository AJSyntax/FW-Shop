<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <!-- Primary Navigation Menu -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    {{-- Link logo to appropriate home based on role --}}
                    <a href="{{ Auth::user()->isAdmin() ? route('admin.dashboard') : route('buyer.home') }}">
                        <x-application-mark class="block h-9 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                    {{-- Dashboard/Home Link based on Role --}}
                    @if (Auth::user()->isAdmin())
                        <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" class="flex items-center">
                            <i class="fas fa-tachometer-alt mr-2"></i>
                            {{ __('Admin Dashboard') }}
                        </x-nav-link>
                    @else
                        <x-nav-link href="{{ route('buyer.home') }}" :active="request()->routeIs('buyer.home')" class="flex items-center">
                            <i class="fas fa-home mr-2"></i>
                            {{ __('Home') }}
                        </x-nav-link>
                    @endif

                    {{-- Common Link for viewing designs --}}
                    <x-nav-link href="{{ route('designs.index') }}" :active="request()->routeIs('designs.index')" class="flex items-center">
                        <i class="fas fa-tshirt mr-2"></i>
                        {{ __('View Designs') }}
                    </x-nav-link>

                    {{-- Admin Specific Links --}}
                    @if (Auth::user()->isAdmin())
                    <div class="hidden sm:flex sm:items-center sm:ml-6">
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div class="flex items-center">
                                        <i class="fas fa-edit mr-2"></i> {{-- Changed Icon --}}
                                        {{ __('Manage Designs') }}
                                    </div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                {{-- Use admin prefixed routes --}}
                                <x-dropdown-link href="{{ route('admin.designs.create') }}" class="flex items-center">
                                    <i class="fas fa-plus mr-2"></i>
                                    {{ __('Add New Design') }}
                                </x-dropdown-link>

                                <x-dropdown-link href="{{ route('admin.designs.manage') }}" class="flex items-center">
                                    <i class="fas fa-tasks mr-2"></i> {{-- Changed Icon --}}
                                    {{ __('Manage All Designs') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>

                    {{-- Use admin prefixed route --}}
                    <x-nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')" class="flex items-center">
                        <i class="fas fa-tags mr-2"></i>
                        {{ __('Manage Categories') }}
                    </x-nav-link>

                    {{-- Admin Order Management Link --}}
                    <x-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.index')" class="flex items-center"> {{-- Keep non-prefixed route for now, controller handles logic --}}
                        <i class="fas fa-file-invoice-dollar mr-2"></i>
                        {{ __('Manage Orders') }}
                    </x-nav-link>

                    {{-- User Management Link --}}
                    <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')" class="flex items-center">
                        <i class="fas fa-users-cog mr-2"></i>
                        {{ __('Manage Users') }}
                    </x-nav-link>

                    {{-- Reports Link --}}
                    <x-nav-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')" class="flex items-center">
                        <i class="fas fa-chart-line mr-2"></i>
                        {{ __('Reports') }}
                    </x-nav-link>

                    {{-- Settings Link --}}
                    <x-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')" class="flex items-center">
                        <i class="fas fa-cog mr-2"></i>
                        {{ __('Settings') }}
                    </x-nav-link>

                    @else {{-- Buyer Specific Links --}}

                    {{-- Buyer Orders Dropdown --}}
                    <div class="hidden sm:flex sm:items-center sm:ml-6"> {{-- Adjusted margin --}}
                        <x-dropdown align="right" width="48">
                            <x-slot name="trigger">
                                <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition duration-150 ease-in-out">
                                    <div class="flex items-center">
                                        <i class="fas fa-shopping-bag mr-2"></i> {{-- Changed Icon --}}
                                        {{ __('My Orders') }}
                                    </div>

                                    <div class="ml-1">
                                        <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                </button>
                            </x-slot>

                            <x-slot name="content">
                                {{-- Buyer specific order links --}}
                                <x-dropdown-link href="{{ route('orders.history') }}" class="flex items-center">
                                    <i class="fas fa-history mr-2"></i>
                                    {{ __('Order History') }}
                                </x-dropdown-link>

                                <x-dropdown-link href="{{ route('orders.track') }}" class="flex items-center">
                                    <i class="fas fa-truck-loading mr-2"></i> {{-- Changed Icon --}}
                                    {{ __('Track My Order') }}
                                </x-dropdown-link>
                            </x-slot>
                        </x-dropdown>
                    </div>
                    @endif {{-- End Admin/Buyer specific links --}}
                </div>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                                        </svg>
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Manage Team') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Team Settings') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Create New Team') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-200"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Switch Teams') }}
                                        </div>

                                        @foreach (Auth::user()->allTeams() as $team)
                                            <x-switchable-team :team="$team" />
                                        @endforeach
                                    @endif
                                </div>
                            </x-slot>
                        </x-dropdown>
                    </div>
                @endif

                <!-- Settings Dropdown -->
                <div class="ms-3 relative">
                    <x-dropdown align="right" width="48">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-8 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none focus:bg-gray-50 active:bg-gray-50 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out">
                    <svg class="size-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-2 pb-3 space-y-1">
            {{-- Responsive Dashboard/Home Link --}}
            @if (Auth::user()->isAdmin())
                <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')" class="flex items-center">
                    <i class="fas fa-tachometer-alt mr-2"></i>
                    {{ __('Admin Dashboard') }}
                </x-responsive-nav-link>
            @else
                <x-responsive-nav-link href="{{ route('buyer.home') }}" :active="request()->routeIs('buyer.home')" class="flex items-center">
                    <i class="fas fa-home mr-2"></i>
                    {{ __('Home') }}
                </x-responsive-nav-link>
            @endif

            {{-- Responsive View Designs Link --}}
            <x-responsive-nav-link href="{{ route('designs.index') }}" :active="request()->routeIs('designs.index')" class="flex items-center">
                <i class="fas fa-tshirt mr-2"></i>
                {{ __('View Designs') }}
            </x-responsive-nav-link>

            {{-- Admin Specific Responsive Links --}}
            @if (Auth::user()->isAdmin())
                {{-- Manage Designs Dropdown --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                        <i class="fas fa-edit mr-2"></i>
                        {{ __('Manage Designs') }}
                        <svg class="ml-auto h-5 w-5" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4">
                        <x-responsive-nav-link href="{{ route('admin.designs.create') }}" class="flex items-center">
                            <i class="fas fa-plus mr-2"></i>
                            {{ __('Add New Design') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('admin.designs.manage') }}" class="flex items-center">
                            <i class="fas fa-tasks mr-2"></i>
                            {{ __('Manage All Designs') }}
                        </x-responsive-nav-link>
                    </div>
                </div>

                {{-- Manage Categories Link --}}
                <x-responsive-nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                    <i class="fas fa-tags mr-2"></i>
                    {{ __('Manage Categories') }}
                </x-responsive-nav-link>

                {{-- Manage Orders Link --}}
                 <x-responsive-nav-link href="{{ route('orders.index') }}" :active="request()->routeIs('orders.index')">
                    <i class="fas fa-file-invoice-dollar mr-2"></i>
                    {{ __('Manage Orders') }}
                </x-responsive-nav-link>

                {{-- Manage Users Link --}}
                <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.*')">
                    <i class="fas fa-users-cog mr-2"></i>
                    {{ __('Manage Users') }}
                </x-responsive-nav-link>

                {{-- Reports Link --}}
                <x-responsive-nav-link href="{{ route('admin.reports.index') }}" :active="request()->routeIs('admin.reports.*')">
                    <i class="fas fa-chart-line mr-2"></i>
                    {{ __('Reports') }}
                </x-responsive-nav-link>

                {{-- Settings Link --}}
                <x-responsive-nav-link href="{{ route('admin.settings.index') }}" :active="request()->routeIs('admin.settings.*')">
                    <i class="fas fa-cog mr-2"></i>
                    {{ __('Settings') }}
                </x-responsive-nav-link>

            @else {{-- Buyer Specific Responsive Links --}}

                {{-- My Orders Dropdown --}}
                <div x-data="{ open: false }" class="space-y-1">
                    <button @click="open = !open" class="w-full flex items-center pl-3 pr-4 py-2 border-l-4 border-transparent text-left text-base font-medium text-gray-600 hover:text-gray-800 hover:bg-gray-50 hover:border-gray-300 focus:outline-none focus:text-gray-800 focus:bg-gray-50 focus:border-gray-300 transition duration-150 ease-in-out">
                        <i class="fas fa-shopping-bag mr-2"></i>
                        {{ __('My Orders') }}
                        <svg class="ml-auto h-5 w-5" :class="{'rotate-180': open}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </button>
                    <div x-show="open" class="pl-4">
                        <x-responsive-nav-link href="{{ route('orders.history') }}" class="flex items-center">
                            <i class="fas fa-history mr-2"></i>
                            {{ __('Order History') }}
                        </x-responsive-nav-link>
                        <x-responsive-nav-link href="{{ route('orders.track') }}" class="flex items-center">
                            <i class="fas fa-truck-loading mr-2"></i>
                            {{ __('Track My Order') }}
                        </x-responsive-nav-link>
                    </div>
                </div>
            @endif {{-- End Admin/Buyer specific responsive links --}}
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-200"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Manage Team') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Team Settings') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Create New Team') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-200"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Switch Teams') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" component="responsive-nav-link" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
