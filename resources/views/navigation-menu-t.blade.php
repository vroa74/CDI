<nav x-data="{ open: false }" class="w-full bg-gray-800 border-b border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="w-full px-4 sm:px-6 lg:px-8">
        <div class="flex min-h-16 items-center justify-between py-2">
            <!-- Logo -->
            <div class="flex shrink-0 items-center">
                <a href="{{ route('dashboard') }}">
                    <x-application-mark class="block h-9 w-auto" />
                </a>
            </div>

            <!-- Navigation Links -->
            <div class="hidden flex-1 items-center justify-center xl:flex">
                <div class="flex flex-wrap items-center justify-center gap-x-4 gap-y-2">
                    <x-nav-link class="px-2" href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                        <i class="ri-dashboard-fill"></i> {{ __('Dashboard') }}
                    </x-nav-link>
                    {{-- <x-nav-link href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.index')">
                        <i class="ri-group-3-fill"></i>   {{ __('Usuarios (CRUD)') }}
                    </x-nav-link> --}}
                    @if(auth()->user()->tipo == 1)
                    <x-nav-link class="px-2" href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.index')">
                        <i class="ri-group-3-fill"></i>   {{ __('Gestión de Usuarios') }}
                    </x-nav-link>
                    @endif
                    <x-nav-link class="px-2" href="{{ route('servicios.index') }}" :active="request()->routeIs('servicios.index')">
                        <i class="ri-service-fill"></i>  {{ __('Gestión de Servicios') }}
                    </x-nav-link>
                    <x-nav-link class="px-2" href="{{ route('inventario.index') }}" :active="request()->routeIs('inventario.index')">
                        <i class="ri-list-settings-line"></i>  {{ __('Gestión de Inventarios') }}
                    </x-nav-link>
                    <x-nav-link class="px-2" href="{{ route('cartasresponsivas.index') }}" :active="request()->routeIs('cartasresponsivas.index')">
                        <i class="ri-list-settings-line"></i>  {{ __('Gestión de Cartas Responsivas') }}
                    </x-nav-link>
                    <x-dropdown align="left" width="48">
                        <x-slot name="trigger">
                            <button type="button" class="inline-flex items-center gap-1 px-2 text-sm font-medium text-gray-300 hover:text-white">
                                <i class="ri-wifi-fill"></i> {{ __('Reportes de Red') }}
                                <i class="ri-arrow-down-s-line"></i>
                            </button>
                        </x-slot>
                        <x-slot name="content">
                            <x-dropdown-link href="{{ route('reportes-incidentes.index') }}">Reportes de incidentes</x-dropdown-link>
                            <x-dropdown-link href="{{ route('edificios.index') }}">Edificios</x-dropdown-link>
                            <x-dropdown-link href="{{ route('lineas-internet.index') }}">Líneas de Internet</x-dropdown-link>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <div class="hidden xl:flex xl:items-center xl:ms-6">
                <!-- Teams Dropdown -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="ms-3 relative">
                        <x-dropdown align="right" width="60">
                            <x-slot name="trigger">
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-300 bg-gray-800 hover:text-gray-100 focus:outline-none focus:bg-gray-700 active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->currentTeam->name }}
                                        <x-icons.lucide name="chevron-down" size="16" class="ms-2 -me-0.5" />
                                    </button>
                                </span>
                            </x-slot>

                            <x-slot name="content">
                                <div class="w-60">
                                    <!-- Team Management -->
                                    <div class="block px-4 py-2 text-xs text-gray-400">
                                        {{ __('Administrar equipo') }}
                                    </div>

                                    <!-- Team Settings -->
                                    <x-dropdown-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}">
                                        {{ __('Configuración del equipo') }}
                                    </x-dropdown-link>

                                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                                        <x-dropdown-link href="{{ route('teams.create') }}">
                                            {{ __('Crear nuevo equipo') }}
                                        </x-dropdown-link>
                                    @endcan

                                    <!-- Team Switcher -->
                                    @if (Auth::user()->allTeams()->count() > 1)
                                        <div class="border-t border-gray-600"></div>

                                        <div class="block px-4 py-2 text-xs text-gray-400">
                                            {{ __('Cambiar de equipo') }}
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
                                    @if(Auth::user()->hasProfilePhoto())
                                        <img class="size-6 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                    @else
                                        <div class="size-6 rounded-full bg-gray-600 flex items-center justify-center">
                                            <span class="text-xs font-medium text-white">{{ Auth::user()->initials }}</span>
                                        </div>
                                    @endif
                                </button>
                            @else
                                <button
                                    type="button"
                                    class="inline-flex h-8 w-8 items-center justify-center rounded-full border border-transparent bg-gray-800 text-gray-300 transition duration-150 ease-in-out hover:text-gray-100 focus:bg-gray-700 focus:outline-none active:bg-gray-700"
                                    title="{{ Auth::user()->name }}"
                                    aria-label="{{ Auth::user()->name }}"
                                >
                                    <x-icons.lucide name="user" size="16" class="shrink-0" />
                                </button>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-4 py-2 text-xs text-gray-400">
                                {{ __('Administrar cuenta') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Perfil') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Cerrar sesión') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-2 flex items-center xl:hidden">
                <button aria-label="Abrir o cerrar men?" :aria-expanded="open" @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-300 hover:bg-gray-700 focus:outline-none focus:bg-gray-700 focus:text-gray-300 transition duration-150 ease-in-out">
                    <svg x-show="!open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                    <svg x-show="open" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95" x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75" x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95" class="max-h-[calc(100dvh-4rem)] overflow-y-auto overscroll-contain xl:hidden">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link href="{{ route('dashboard') }}" :active="request()->routeIs('dashboard')">
                <i class="ri-dashboard-fill"></i> {{ __('Dashboard') }}
            </x-responsive-nav-link>
            @if(auth()->user()->tipo == 1)
            <x-responsive-nav-link href="{{ route('usuarios.index') }}" :active="request()->routeIs('usuarios.index')">
                <i class="ri-group-3-fill"></i>   {{ __('Gestión de Usuarios') }}
            </x-responsive-nav-link>
            @endif
            <x-responsive-nav-link href="{{ route('servicios.index') }}" :active="request()->routeIs('servicios.index')">
                <i class="ri-service-fill"></i>  {{ __('Gestión de Servicios') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('inventario.index') }}" :active="request()->routeIs('inventario.index')">
                <i class="ri-list-settings-line"></i>  {{ __('Gestión de Inventarios') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('cartasresponsivas.index') }}" :active="request()->routeIs('cartasresponsivas.index')">
                <i class="ri-list-settings-line"></i>  {{ __('Gestión de Cartas Responsivas') }}
            </x-responsive-nav-link>
            <x-network-reports-mobile-menu />
        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-4 pb-1 border-t border-gray-600">
            <div class="flex items-center px-4">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-3">
                        @if(Auth::user()->hasProfilePhoto())
                            <img class="size-6 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                        @else
                            <div class="size-6 rounded-full bg-gray-600 flex items-center justify-center">
                                <span class="text-xs font-medium text-white">{{ Auth::user()->initials }}</span>
                            </div>
                        @endif
                    </div>
                @endif

                <div class="min-w-0">
                    <div class="truncate font-medium text-base text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="truncate font-medium text-sm text-gray-400">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Perfil') }}
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
                        {{ __('Cerrar sesión') }}
                    </x-responsive-nav-link>
                </form>

                <!-- Team Management -->
                @if (Laravel\Jetstream\Jetstream::hasTeamFeatures())
                    <div class="border-t border-gray-600"></div>

                    <div class="block px-4 py-2 text-xs text-gray-400">
                        {{ __('Administrar equipo') }}
                    </div>

                    <!-- Team Settings -->
                    <x-responsive-nav-link href="{{ route('teams.show', Auth::user()->currentTeam->id) }}" :active="request()->routeIs('teams.show')">
                        {{ __('Configuración del equipo') }}
                    </x-responsive-nav-link>

                    @can('create', Laravel\Jetstream\Jetstream::newTeamModel())
                        <x-responsive-nav-link href="{{ route('teams.create') }}" :active="request()->routeIs('teams.create')">
                            {{ __('Crear nuevo equipo') }}
                        </x-responsive-nav-link>
                    @endcan

                    <!-- Team Switcher -->
                    @if (Auth::user()->allTeams()->count() > 1)
                        <div class="border-t border-gray-600"></div>

                        <div class="block px-4 py-2 text-xs text-gray-400">
                            {{ __('Cambiar de equipo') }}
                        </div>

                        @foreach (Auth::user()->allTeams() as $team)
                            <x-switchable-team :team="$team" />
                        @endforeach
                    @endif
                @endif
            </div>
        </div>
    </div>
</nav>
