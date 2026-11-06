<!-- Modal de Selección de Inventario -->
@if ($showInventoryModal)
    <div class="app-modal service-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-7xl max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-medium text-white">
                        Selección de Inventario
                        <div class="text-xs text-gray-400 font-normal mt-1">
                            {{ $inventoryModalType ?? '' }},
                            {{ $inventoryParam1 ?? '' }},
                            {{ $inventoryParam2 ?? '' }},
                            {{ $inventoryParam3 ?? '' }},
                            {{ $inventoryParam4 ?? '' }},
                            {{ $inventoryParam5 ?? '' }},
                        </div>
                    </h3>
                    <button wire:click="closeInventoryModal" class="modal-action text-white transition-colors bg-gray-600 hover:bg-gray-700">
                        <x-lucide name="x" class="w-5 h-5 sm:w-6 sm:h-6" />
                    </button>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                <!-- Campos de búsqueda para inventario -->
                <div class="mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por NI
                            </label>
                            <input wire:model.live="inventorySearchNi" type="text" placeholder="NI..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por SN
                            </label>
                            <input wire:model.live="inventorySearchSn" type="text" placeholder="SN..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por tipo
                            </label>
                            <input wire:model.live="inventorySearchType" type="text" placeholder="Tipo..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por artículo
                            </label>
                            <input wire:model.live="inventorySearchArticulo" type="text" placeholder="Artículo..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por nombre de usuario
                            </label>
                            <input wire:model.live="inventorySearchUserName" type="text" placeholder="Nombre de usuario..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por dirección de usuario
                            </label>
                            <input wire:model.live="inventorySearchUserDirection" type="text" placeholder="Dirección de usuario..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                </div>
                
                <!-- Tabla de inventarios -->
                <div class="bg-gray-700 rounded-3xl overflow-hidden border border-gray-600">
                    <div class="min-w-0 w-full max-w-full">
                        <table class="responsive-table w-full rounded-3xl overflow-hidden">
                            <thead class="bg-gray-600">
                                <tr>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        NI / SN
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        TYPE
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        ARTICULO
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        USUARIO / DIRECCIÓN
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-700 divide-y divide-gray-600">
                                @forelse($filteredInventories as $inv)
                                    <tr wire:key="inv-{{ $inv->id }}" class="hover:bg-gray-600 transition-colors">
                                        <td data-label="NI / SN" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            <div class="flex flex-col space-y-1">
                                                <div><span class="text-blue-400 font-medium">NI:</span> {{ $inv->ni ?? 'N/A' }}</div>
                                                <div><span class="text-green-400 font-medium">SN:</span> {{ $inv->ns ?? 'N/A' }}</div>
                                            </div>
                                            <!-- Información adicional visible en móvil -->

                                        </td>
                                        <td data-label="TYPE" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $inv->type ?? 'N/A' }}
                                        </td>
                                        <td data-label="ARTICULO" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $inv->articulo ?? 'N/A' }}
                                        </td>
                                        <td data-label="USUARIO / DIRECCIÓN" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            @if ($inv->assignedUser)
                                                <div class="flex flex-col space-y-1">
                                                    <div><span class="text-blue-400 font-medium">Usuario:</span> {{ $inv->assignedUser->name }}</div>
                                                    <div><span class="text-[10px] text-gray-400">{{ $inv->assignedUser->position ?? 'N/A' }}</span></div>
                                                    <div><span class="text-green-400 font-medium">Dirección:</span> {{ $inv->assignedUser->direction ?? 'N/A' }}</div>
                                                </div>
                                            @elseif($inv->responsible)
                                                <div class="flex flex-col space-y-1">
                                                    <div><span class="text-blue-400 font-medium">Usuario:</span> {{ $inv->responsible->name }}</div>
                                                    <div><span class="text-[10px] text-gray-400">{{ $inv->responsible->position ?? 'N/A' }}</span></div>
                                                    <div><span class="text-green-400 font-medium">Dirección:</span> {{ $inv->responsible->direction ?? 'N/A' }}</div>
                                                </div>
                                            @else
                                                <div class="flex flex-col space-y-1">
                                                    <div><span class="text-blue-400 font-medium">Usuario:</span> <span class="text-gray-500">Sin usuario</span></div>
                                                    <div><span class="text-green-400 font-medium">Dirección:</span> <span class="text-gray-500">N/A</span></div>
                                                </div>
                                            @endif
                                        </td>
                                        <td data-label="Acciones" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs font-medium">
                                            <button wire:click="selectInventory({{ $inv->id }})"
                                                class="modal-action bg-green-600 text-white hover:bg-green-700 transition-colors"
                                                title="Seleccionar">
                                                <i class="ri-invision-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-4 sm:px-6 py-8 sm:py-12 text-center text-gray-400">
                                            <x-lucide name="package-x" class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-3 sm:mb-4 text-gray-600" />
                                            <p class="text-sm sm:text-lg">No se encontraron inventarios</p>
                                            <p class="text-xs sm:text-sm">Intenta con otros términos de búsqueda</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                
                <!-- Botones del modal -->
                <div class="flex justify-end gap-2 sm:gap-3 pt-4 border-t border-gray-700 mt-4">
                    <button type="button" wire:click="closeInventoryModal"
                        class="modal-action text-white px-3 sm:px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif 