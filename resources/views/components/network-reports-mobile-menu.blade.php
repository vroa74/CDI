<div>
    <div x-data="{ expanded: @js(request()->routeIs('reportes-incidentes.*', 'edificios.*', 'lineas-internet.*')) }">
        <button type="button" @click="expanded = !expanded" :aria-expanded="expanded" aria-controls="mobile-network-reports"
            class="flex min-h-11 w-full items-center justify-between gap-3 border-l-4 border-transparent px-4 py-3 text-left text-base font-medium text-gray-300 hover:bg-gray-700 hover:text-white focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
            <span><i class="ri-wifi-fill" aria-hidden="true"></i> Reportes de Red</span>
            <x-lucide name="chevron-down" class="h-5 w-5 shrink-0 transition-transform" x-bind:class="{ 'rotate-180': expanded }" />
        </button>
        <div id="mobile-network-reports" x-show="expanded" x-cloak class="space-y-1 border-l border-gray-600 pl-4">
            <x-responsive-nav-link href="{{ route('reportes-incidentes.index') }}" :active="request()->routeIs('reportes-incidentes.*')">Reportes de incidentes</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('edificios.index') }}" :active="request()->routeIs('edificios.*')">Edificios</x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('lineas-internet.index') }}" :active="request()->routeIs('lineas-internet.*')">Líneas de Internet</x-responsive-nav-link>
        </div>
    </div>
</div>
