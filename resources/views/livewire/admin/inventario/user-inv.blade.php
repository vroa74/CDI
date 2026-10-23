<div class="p-4">
    <!-- Acordeón con tabla de filtros (solo vista, sin funcionalidad) -->
    <div x-data="{ open: true }" class="mb-2">
        <div class="bg-gray-800 rounded-3xl overflow-hidden shadow-lg border border-gray-700">
            <!-- Encabezado del acordeón -->
            <button class="w-full px-2 py-1 bg-gray-700 hover:bg-gray-600 transition-colors flex items-center justify-between">
                <h3 class="text-lg font-medium text-white">Filtros de Inventario</h3>
                <svg class="w-5 h-5 text-gray-300 transform transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </button>
            <div class="p-2">
                <div class="overflow-x-auto rounded-3xl">
                    <table class="min-w-full border-separate border-spacing-4 bg-transparent table-fixed">
                        <tbody>
                            <tr>
                                <td class="w-1/6 h-16 bg-gray-900 border-2 border-pink-400 rounded-3xl text-center align-middle text-pink-300 p-0">
                                    <div class="flex flex-col justify-center items-center h-full w-full px-2">
                                        <label class="text-xs text-pink-200 mb-0.5 text-center w-full">Resguardante</label>
                                        <div class="flex items-center w-full justify-center gap-2">
                                            <input type="text"
                                                placeholder="Buscar..."
                                                class="w-3/4 max-w-xs px-3 py-1 rounded-full bg-gray-800 border border-pink-400 text-pink-200 placeholder-pink-400 focus:ring-2 focus:ring-pink-500 focus:border-transparent text-sm mx-auto"
                                                style="min-width: 60px;"
                                                disabled
                                            />
                                            <button type="button" class="ml-1 px-2 py-1 rounded-lg bg-pink-500 text-white text-xs font-semibold opacity-60 cursor-not-allowed" disabled>Asignar</button>
                                        </div>
                                        </div>
                                    </td>
                                <td class="w-1/6 h-16 bg-gray-900 border-2 border-cyan-400 rounded-3xl text-center align-middle text-cyan-300 font-bold text-lg">2</td>
                                <td class="w-1/6 h-16 bg-gray-900 border-2 border-yellow-400 rounded-3xl text-center align-middle text-yellow-300 font-bold text-lg">3</td>
                                <td class="w-1/6 h-16 bg-gray-900 border-2 border-green-400 rounded-3xl text-center align-middle text-green-300 font-bold text-lg">4</td>
                                <td class="w-1/6 h-16 bg-gray-900 border-2 border-blue-400 rounded-3xl text-center align-middle text-blue-300 font-bold text-lg">5</td>
                                <td class="w-1/6 h-16 bg-gray-900 border-2 border-red-400 rounded-3xl text-center align-middle text-red-300 font-bold text-lg">6</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
        </div>
    </div>
    <!-- Contenedor de las dos tablas lado a lado -->
    <div class="flex gap-4">
        <!-- Tabla de Usuarios (25% del ancho) -->
        <div class="w-1/4 flex flex-col">
            <div class="bg-gray-800 rounded-3xl overflow-hidden shadow-lg border border-gray-700 flex flex-col flex-1">
                <div class="px-4 py-3 bg-gray-700 border-b border-gray-600">
                    <h3 class="text-lg font-medium text-white">Usuarios</h3>
                </div>
                <div class="flex-1 overflow-y-auto">
                    <table class="min-w-full border-2 border-gray-700 rounded-3xl">
                        <thead class="bg-gray-700/50 sticky top-0">
                            <tr class="rounded-3xl border-2 border-gray-700">
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">ID</th>
                                <th class="px-3 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Nombre</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($users as $user)
                                <tr class="hover:bg-gray-700 transition-colors cursor-pointer">
                                    <td class="px-3 py-2 text-sm text-gray-300">{{ $user->id }}</td>
                                    <td class="px-3 py-2 text-sm text-white font-medium">
                                        {{ $user->name }}
                                        <div class="text-xs text-yellow-300">{{ $user->position ?? 'Sin cargo' }}</div>
                                        <div class="text-xs text-cyan-300">{{ $user->direction ?? 'Sin dirección' }}</div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-3 py-4 text-center text-gray-400">
                                        <x-lucide name="users" class="w-8 h-8 mx-auto mb-2 text-gray-600" />
                                        <p class="text-sm">No se encontraron usuarios</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($users->hasPages())
                    <div class="px-3 py-2 bg-gray-700 border-t border-gray-600">
                        {{ $users->links() }}
                    </div>
                @endif
            </div>
        </div>
        <!-- Tabla de Inventario (75% del ancho) -->
        <div class="w-3/4 flex flex-col">
            <div class="bg-gray-800 rounded-3xl overflow-hidden shadow-lg border border-gray-700 flex flex-col flex-1">
                <div class="px-4 py-3 bg-gray-700 border-b border-gray-600">
                    <h3 class="text-lg font-medium text-white">
                        Inventario
                    </h3>
                </div>
                <div class="overflow-x-auto flex-1">
                    <table class="min-w-full">
                        <thead class="bg-gray-700/50 sticky top-0">
                            <tr>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Artículo</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Detalles</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Resguardante</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Ubicación</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Tipo</th>
                                <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">Estado</th>
                            </tr>
                        </thead>
                        <tbody class="bg-gray-800 divide-y divide-gray-700">
                            @forelse($inventories as $item)
                                <tr class="hover:bg-gray-700 transition-colors">
                                    <td class="px-3 py-4 text-sm text-gray-300">
                                        <div class="font-medium text-white"> id:{{ $item->id ?? 'N/A' }}</div>
                                        <div class="font-medium text-white">{{ $item->articulo ?? 'N/A' }}</div>
                                        <div class="text-xs text-gray-400">NI: {{ $item->ni ?? 'N/A' }}</div>
                                        <div class="mt-2 space-y-1">
                                            <div><span class="font-semibold text-gray-400">NS:</span> {{ $item->ns ?? 'N/A' }}</div>
                                            <div><span class="font-semibold text-gray-400">Marca:</span> {{ $item->marca ?? 'N/A' }}</div>
                                            <div><span class="font-semibold text-gray-400">Modelo:</span> {{ $item->modelo ?? 'N/A' }}</div>
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-300">
                                        <div class="space-y-2">
                                            @if($item->assignedUser && is_object($item->assignedUser))
                                                <div>
                                                    <div class="text-xs text-gray-400">Usuario:</div>
                                                    <div class="font-medium text-yellow-400">ID: {{ $item->assignedUser->id }}</div>
                                                    <div class="font-medium text-yellow-400">{{ $item->assignedUser->name }}</div>
                                                    <div class="text-xs text-yellow-300">Posición: {{ $item->assignedUser->position ?? 'Sin posición' }}</div>
                                                    <div class="text-xs text-yellow-300">Dirección: {{ $item->assignedUser->direction ?? 'Sin dirección' }}</div>
                                                </div>
                                            @endif
                                            @if($item->responsible && is_object($item->responsible))
                                                <div>
                                                    <div class="text-xs text-gray-400">Resguardante:</div>
                                                    <div class="font-medium text-pink-400">ID: {{ $item->responsible->id }}</div>
                                                    <div class="font-medium text-pink-400">{{ $item->responsible->name }}</div>
                                                    <div class="text-xs text-pink-300">Posición: {{ $item->responsible->position ?? 'Sin posición' }}</div>
                                                    <div class="text-xs text-pink-300">Dirección: {{ $item->responsible->direction ?? 'Sin dirección' }}</div>
                                                </div>
                                            @endif
                                        </div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-300">
                                        <div class="font-medium text-white">
                                            @if($item->responsible && is_object($item->responsible))
                                                {{ $item->responsible->name }}
                                            @else
                                                {{ $item->resguardante ?? 'N/A' }}
                                            @endif
                                        </div>
                                        <div class="text-xs text-gray-400">{{ $item->cargo ?? 'Sin cargo' }}</div>
                                    </td>
                                    <td class="px-3 py-4 text-sm text-gray-300">
                                        <div class="font-medium text-white">{{ $item->dir ?? 'N/A' }}</div>
                                    </td>
                                    <td class="px-3 py-4 text-center">                                      
                                        @if($item->is_pc)
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                PC
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                no es PC
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-3 py-4 text-sm">
                                        @if($item->status)
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                Activo
                                            </span>
                                        @else
                                            <span class="px-2.5 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-black">
                                                Inactivo
                                            </span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-3 py-12 text-center text-gray-400">
                                        <x-lucide name="database-zap" class="w-12 h-12 mx-auto mb-4 text-gray-600" />
                                        <p class="text-lg">
                                                No se encontraron artículos para este usuario
                                        </p>
                                        <p class="text-sm">
                                                Este usuario no tiene artículos asignados
                                        </p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                @if($inventories->hasPages())
                    <div class="px-3 py-4 bg-gray-700 border-t border-gray-600">
                        {{ $inventories->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

@script
<script>
    Livewire.on('swal:confirm-asignacion-resguardante', function () {
        Swal.fire({
            title: '¿Asignar resguardante?',
            text: 'Se actualizarán los campos user_id y res_id de los registros filtrados en inventario. ¿Deseas continuar?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Sí, aplicar cambios',
            cancelButtonText: 'No, cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                Livewire.dispatch('asignarResguardante');
            } else {
                Livewire.dispatch('asignacionResguardanteCancelada');
            }
        });
    });
    Livewire.on('swal:mensaje', function (data) {
        Swal.fire({
            title: data.titulo,
            text: data.mensaje,
            icon: data.tipo || 'info',
            confirmButtonText: 'OK'
        });
    });
</script>
@endscript