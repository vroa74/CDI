<!-- Modal de Selección de Usuario -->
@if ($showModal)
    <div class="app-modal service-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-full max-h-[90vh] overflow-y-auto">
            <div class="p-4 sm:p-6 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-medium text-white">
                        Selección de usuario
                        <div class="text-xs text-gray-400 font-normal mt-1">
                            {{ $modalType ?? '' }},
                            {{ $modalParam1 ?? '' }},
                            {{ $modalParam2 ?? '' }},
                            {{ $modalParam4 ?? '' }},
                        </div>
                    </h3>
                    <button wire:click="closeModal" class="modal-action text-white transition-colors bg-gray-600 hover:bg-gray-700">
                        <x-lucide name="x" class="w-5 h-5 sm:w-6 sm:h-6" />
                    </button>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                <!-- Campos de búsqueda -->
                <div class="mb-4">
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por nombre
                            </label>
                            <input wire:model.live="userSearchName" type="text" placeholder="Nombre..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por posición
                            </label>
                            <input wire:model.live="userSearchPosition" type="text" placeholder="Posición..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por dirección
                            </label>
                            <input wire:model.live="userSearchDirection" type="text" placeholder="Dirección..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                                Buscar por nivel
                            </label>
                            <input wire:model.live="userSearchLvl" type="text" placeholder="Nivel..."
                                class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm">
                        </div>
                    </div>
                </div>
                
                <!-- Tabla de usuarios -->
                <div class="bg-gray-700 rounded-3xl overflow-hidden border border-gray-600">
                    <div class="min-w-0 w-full max-w-full">
                        <table class="responsive-table w-full rounded-3xl overflow-hidden">
                            <thead class="bg-gray-600">
                                <tr>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Nombre
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        RFC
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Posición
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Dirección
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Sexo
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Tipo
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Nivel
                                    </th>
                                    <th class="px-2 sm:px-4 py-2 sm:py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Acciones
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-700 divide-y divide-gray-600">
                                @forelse($filteredUsers as $user)
                                    <tr wire:key="user-{{ $user->id }}" class="hover:bg-gray-600 transition-colors">
                                        <td data-label="Nombre" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            <div class="font-medium text-white text-xs">{{ $user->name }}</div>
                                            <!-- Información adicional visible en móvil -->

                                        </td>
                                        <td data-label="RFC" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $user->rfc ?? 'N/A' }}
                                        </td>
                                        <td data-label="Posición" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $user->position ?? 'N/A' }}
                                        </td>
                                        <td data-label="Dirección" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $user->direction ?? 'N/A' }}
                                        </td>
                                        <td data-label="Sexo" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $user->sex ?? 'N/A' }}
                                        </td>
                                        <td data-label="Tipo" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $user->tipo ?? 'N/A' }}
                                        </td>
                                        <td data-label="Nivel" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs text-gray-300">
                                            {{ $user->lvl ?? 'N/A' }}
                                        </td>
                                        <td data-label="Acciones" class="px-2 sm:px-4 py-3 sm:py-4 whitespace-normal text-xs font-medium">
                                            <button wire:click="selectUser({{ $user->id }}, @js($user->name))"
                                                class="modal-action bg-blue-600 text-white hover:bg-blue-700 transition-colors"
                                                title="Seleccionar">
                                                <i class="ri-shield-user-line"></i>
                                            </button>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 sm:px-6 py-8 sm:py-12 text-center text-gray-400">
                                            <x-lucide name="user-x" class="w-8 h-8 sm:w-12 sm:h-12 mx-auto mb-3 sm:mb-4 text-gray-600" />
                                            <p class="text-sm sm:text-lg">No se encontraron usuarios</p>
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
                    <button type="button" wire:click="closeModal"
                        class="modal-action text-white px-3 sm:px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors text-sm">
                        Cancelar
                    </button>
                </div>
            </div>
        </div>
    </div>
@endif 