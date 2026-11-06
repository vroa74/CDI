<div class="min-h-screen bg-gray-900 p-4">
    <div class="flex flex-col xl:flex-row gap-6 h-full">
        <!-- Primera columna - Formulario actual (67%) -->
        <div class="w-full min-w-0 xl:w-[67%] xl:flex-1">
            <div class="bg-gray-800 rounded-lg mb-1 shadow-xl w-full max-w-full px-3 sm:px-6 mx-auto">
                <!-- Mensajes de error y éxito -->
                @if (session()->has('error'))
                    <div class="p-4 mb-4 bg-red-600 border border-red-500 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-lucide name="alert-triangle" class="w-5 h-5 text-red-200" />
                                    <p class="text-white font-medium">Error:</p>
                                </div>
                                <div class="text-red-100 text-sm whitespace-pre-line">{{ session('error') }}</div>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-red-200 hover:text-white ml-4">
                                <x-lucide name="x" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                @endif

                @if (session()->has('message'))
                    <div class="p-4 mb-4 bg-green-600 border border-green-500 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-lucide name="check-circle" class="w-5 h-5 text-green-200" />
                                    <p class="text-white font-medium">Éxito:</p>
                                </div>
                                <div class="text-green-100 text-sm">{{ session('message') }}</div>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-green-200 hover:text-white ml-4">
                                <x-lucide name="x" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                @endif

                <!-- Advertencias de configuración del servidor -->
                @if (!empty($serverWarnings))
                    <div class="p-4 mb-4 bg-yellow-600 border border-yellow-500 rounded-lg">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-2">
                                    <x-lucide name="alert-triangle" class="w-5 h-5 text-yellow-200" />
                                    <p class="text-white font-medium">Advertencias de configuración:</p>
                                </div>
                                <ul class="text-yellow-100 text-sm list-disc list-inside space-y-1">
                                    @foreach ($serverWarnings as $warning)
                                        <li>{{ $warning }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            <button onclick="this.parentElement.parentElement.remove()" class="text-yellow-200 hover:text-white ml-4">
                                <x-lucide name="x" class="w-5 h-5" />
                            </button>
                        </div>
                    </div>
                @endif

                <div class="p-1 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white">
                            Editar servicio
                        </h3>
                        <a href="{{ route('servicios.index') }}" class="text-gray-400 hover:text-white transition-colors">
                            <x-lucide name="x" class="w-6 h-6" />
                        </a>
                    </div>
                </div>
                {{-- ========================================================================================================================================================================================================= --}}
                <form wire:submit.prevent="updateService" class="p-6 space-y-6">
                    <!-- Información básica -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="id_s" class="block text-sm font-medium text-gray-300 mb-2">
                                ID del servicio
                            </label>
                            <input wire:model="id_s" type="text" id="id_s"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="ID del servicio">
                            @error('id_s')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <label for="F_serv" class="block text-sm font-medium text-gray-300 mb-2">
                                Fecha de servicio
                            </label>
                            <input wire:model="F_serv" type="date" id="F_serv"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            @error('F_serv')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{-- ========================================================================================================================================================================================================= --}}
                    <!-- Descripción del servicio -->
                    <div>
                        <div class="flex items-center gap-2 mb-2">
                            <label for="obj_sol" class="block text-sm font-medium text-gray-300">
                                Objetivo de la solicitud
                            </label>
                            <button type="button" wire:click="openUserModal('objetivo', 'null', 'null', null, 'null')"
                                class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                <x-lucide name="users" class="w-4 h-4" />
                            </button>
                            <!-- Botón para abrir el modal de inventario -->
                            <button type="button" wire:click="openInventoryModal('objetivo')"
                                class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                <x-lucide name="list-search" class="w-4 h-4" />
                            </button>

                        </div>
                        <textarea wire:model.defer="obj_sol" wire:blur="$refresh" id="obj_sol" rows="9"
                            class="w-full px-3 py-2 text-xs bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                            placeholder="Describa el objetivo de la solicitud"></textarea>
                        @error('obj_sol')
                            <span class="text-red-500">{{ $message }}</span>
                        @enderror
                    </div>
                    {{-- ========================================================================================================================================================================================================= --}}
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <label for="actividades" class="block text-sm font-medium text-gray-300 mb-2">
                                    Actividades realizadas
                                </label>
                                <button type="button"
                                    wire:click="openUserModal('actividades', 'null', 'null', null, 'null')"
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                    <x-lucide name="users" class="w-4 h-4" />
                                </button>
                                <!-- Botón para abrir el modal de inventario -->
                                <button type="button" wire:click="openInventoryModal('actividades')"
                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                    <x-lucide name="list-search" class="w-4 h-4" />
                                </button>
                            </div>
                            <textarea wire:model.defer="actividades" wire:blur="$refresh" id="actividades" rows="9"
                                class="w-full px-3 py-2 text-xs bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Describa las actividades realizadas"></textarea>
                            @error('actividades')
                                <span class="text-red-500">{{ $message }}</span>
                            @enderror
                        </div>
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <label for="observaciones" class="block text-sm font-medium text-gray-300 mb-2">
                                    Observaciones
                                </label>
                                <button type="button"
                                    wire:click="openUserModal('observaciones', 'null', 'null', null, 'null')"
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                    <x-lucide name="users" class="w-4 h-4" />
                                </button>
                                <!-- Botón para abrir el modal de inventario -->
                                <button type="button" wire:click="openInventoryModal('observaciones')"
                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                    <x-lucide name="list-search" class="w-4 h-4" />
                                </button>
                            </div>
                            <textarea wire:model.defer="observaciones" wire:blur="$refresh" id="observaciones" rows="9"
                                class="w-full px-3 py-2 text-xs bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Observaciones adicionales"></textarea>
                            @error('observaciones')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{-- ========================================================================================================================================================================================================= --}}
                    <!-- Tipo de Servicio y Via de Solicitud en dos columnas -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Tipo de servicio -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-3">
                                Tipo de servicio
                            </label>
                            <div class="flex flex-wrap gap-3 items-center">
                                <label class="flex items-center">
                                    <input wire:model="correctivo" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Correctivo</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="preventivo" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Preventivo</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="transparencia" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Transparencia</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="a_tec" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">A. Técnico</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="web_ins" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Web/Ins</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="print" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Impresión</span>
                                </label>
                            </div>
                            @error('tipo_servicio')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        {{-- ========================================================================================================================================================================================================= --}}
                        <!-- Via de solicitud -->
                        <div>
                            <label class="block text-sm font-medium text-gray-300 mb-3">
                                Via de Solicitud
                            </label>
                            <div class="flex flex-wrap gap-3 items-center">
                                <label class="flex items-center">
                                    <input wire:model="email" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Correo</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="tel" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Teléfono</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="sol_ser" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Solicitud de Servicio</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model="oficio" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Oficio</span>
                                </label>
                                <label class="flex items-center">
                                    <input wire:model.live="calendario" type="checkbox"
                                        class="w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2">
                                    <span class="ml-2 text-sm text-gray-300">Calendario</span>
                                </label>
                            </div>
                            @error('via_solicitud')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    {{-- ========================================================================================================================================================================================================= --}}
                    <!-- Mantenimiento -->
                    @if ($calendario)
                        <div>
                            <div class="flex items-center gap-2 mb-2">
                                <label for="observaciones" class="block text-sm font-medium text-gray-300 mb-2">
                                    Mantenimiento
                                </label>
                                <button type="button"
                                    wire:click="openUserModal('mantenimiento', 'null', 'null', null, 'null')"
                                    class="px-3 py-1 bg-blue-600 hover:bg-blue-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                    <x-lucide name="users" class="w-4 h-4" />
                                </button>
                                <!-- Botón para abrir el modal de inventario -->
                                <button type="button" wire:click="openInventoryModal('mantenimiento')"
                                    class="px-3 py-1 bg-green-600 hover:bg-green-700 text-white text-sm rounded-lg transition-colors flex items-center gap-1">
                                    <x-lucide name="list-search" class="w-4 h-4" />
                                </button>
                            </div>
                            <textarea wire:model.defer="mantenimiento" wire:blur="$refresh" id="mantenimiento" rows="9"
                                class="w-full px-3 py-2 text-xs bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Detalles de mantenimiento"></textarea>
                            @error('mantenimiento')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                    @endif
                    <!-- Usuarios involucrados -->
                    <div>
                        <label class="block text-sm font-medium text-gray-300 mb-3">
                            Usuarios involucrados
                        </label>
                        <!-- Tabla de 3 columnas para Solicitante, Efectuó y VºBº -->
                        <div class="w-full flex gap-2 mb-4">
                            <div class="flex-1 bg-gray-700 rounded-lg p-2 text-center">
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <label class="text-xs font-medium text-gray-300">Solicitante</label>
                                    <button type="button"
                                        wire:click="openUserModal('Solicitante', 'null', 'null', null, 'null')"
                                        class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                        <x-lucide name="users" class="w-3 h-3" />
                                    </button>
                                </div>
                                <div class="text-xs text-gray-400 leading-tight">
                                    {{ $solicitante_name }}<br>
                                    {{ $solicitante_position }}<br>
                                    {{ $solicitante_direction }}
                                    @error('solicitante_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex-1 bg-gray-700 rounded-lg p-2 text-center">
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <label class="text-xs font-medium text-gray-300">Efectuó</label>
                                    <button type="button"
                                        wire:click="openUserModal('efectuo', 'null', 'infor', null, 'null')"
                                        class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                        <x-lucide name="users" class="w-3 h-3" />
                                    </button>
                                </div>
                                <div class="text-xs text-gray-400 leading-tight">
                                    {{ $efectuo_name }}<br>
                                    {{ $efectuo_position }}<br>
                                    {{ $efectuo_direction }}
                                    @error('efectuo_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                            <div class="flex-1 bg-gray-700 rounded-lg p-2 text-center">
                                <div class="flex items-center justify-center gap-2 mb-1">
                                    <label class="text-xs font-medium text-gray-300">VºBº</label>
                                    <button type="button"
                                        wire:click="openUserModal('vobo', 'null', 'infor', 4, 'null')"
                                        class="px-2 py-1 bg-blue-600 hover:bg-blue-700 text-white text-xs rounded transition-colors">
                                        <x-lucide name="users" class="w-3 h-3" />
                                    </button>
                                </div>
                                <div class="text-xs text-gray-400 leading-tight">
                                    {{ $vobo_name }}<br>
                                    {{ $vobo_position }}<br>
                                    {{ $vobo_direction }}
                                    @error('vobo_id')
                                        <span class="text-red-500">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>



                    </div>
                    {{-- ========================================================================================================================================================================================================= --}}
                    
                    <!-- Sección de Inventarios - Solo visible cuando Preventivo y Calendario están seleccionados -->
                    @if($preventivo && $calendario)
                        <div class="bg-gray-700 rounded-lg p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-semibold text-white flex items-center gap-2">
                                    <x-lucide name="package" class="w-5 h-5" />
                                    Inventarios Asociados
                                    <span class="text-xs bg-green-600 text-white px-2 py-1 rounded-full">
                                        Mantenimiento Preventivo Calendarizado
                                    </span>
                                </h3>
                                <button type="button" wire:click="openInventorySelection"
                                    class="px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors flex items-center gap-2">
                                    <x-lucide name="plus" class="w-4 h-4" />
                                    Agregar Inventario
                                </button>
                            </div>

                        @if($this->selectedInventoriesData->count() > 0)
                            <div class="space-y-1 max-h-48 overflow-y-auto">
                                @foreach($this->selectedInventoriesData as $inventory)
                                    <div class="bg-gray-600 rounded p-2 border border-gray-500">
                                        <div class="flex items-center justify-between">
                                            <div class="flex-1">
                                                <div class="text-white font-medium text-xs">{{ $inventory->articulo ?? 'Sin artículo' }}</div>
                                                <div class="text-xs text-gray-300">
                                                    NI: {{ $inventory->ni ?? 'N/A' }} | SN: {{ $inventory->ns ?? 'N/A' }} | {{ $inventory->marca ?? 'N/A' }} {{ $inventory->modelo ?? 'N/A' }}
                                                </div>
                                                @if($inventory->assignedUser)
                                                    <div class="text-xs text-gray-400">
                                                        {{ $inventory->assignedUser->name }} - {{ $inventory->assignedUser->direction }}
                                                    </div>
                                                @endif
                                            </div>
                                            <button type="button" wire:click="removeInventoryFromService({{ $inventory->id }})"
                                                class="text-red-400 hover:text-red-300 transition-colors ml-2">
                                                <x-lucide name="trash-2" class="w-3 h-3" />
                                            </button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-4 text-gray-400">
                                <x-lucide name="package" class="w-8 h-8 mx-auto mb-2 opacity-50" />
                                <p class="text-sm">No hay inventarios asociados a este servicio.</p>
                                <p class="text-xs">Haz clic en "Agregar Inventario" para asociar elementos del inventario.</p>
                            </div>
                        @endif
                        </div>
                    @endif

                    {{-- ========================================================================================================================================================================================================= --}}
                    <!-- Botones -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-700">
                        <a href="{{ route('servicios.index') }}"
                            class="px-4 py-2 text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                            Cancelar
                        </a>
                        <button type="submit"
                            class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center gap-2">
                            <x-lucide name="save" class="w-4 h-4" />
                            Actualizar Servicio
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tercera columna - Fotos del Servicio (33%) -->
        <div class="w-full min-w-0 xl:w-[33%] xl:flex-1">
            <div class="p-6 bg-gray-800 rounded-lg shadow h-full overflow-y-auto border border-gray-700">
                <h3 class="text-gray-100 text-lg font-medium mb-4">Fotos de Servicios</h3>

                <!-- Header azul con identificador -->
                <div
                    class="rounded-t-xl bg-blue-600 text-white text-base font-semibold px-4 py-2 mb-2 border border-blue-600">
                    {{ $id_s }}
                </div>

                <!-- Botón para agregar foto - Debajo de la barra azul -->
                <div class="mb-3">
                    <button type="button" wire:click="openPhotoForm"
                        class="add-photo-btn w-full text-white px-4 py-3 rounded-lg text-sm flex items-center justify-center gap-2 transition-all duration-200 hover:scale-105 font-medium">
                        <x-lucide name="plus" class="w-4 h-4" />
                        Agregar foto
                    </button>
                </div>

                <!-- Contenedor de fotos -->
                <div class="border border-gray-500 rounded-xl bg-gray-900 px-2 py-3 mb-4">
                    @if (count($servicePhotos) > 0)
                        <div class="photos-grid mb-3">
                            @foreach ($servicePhotos as $index => $photo)
                                <div class="photo-card flex flex-col items-center bg-gray-800 border border-gray-600 rounded-lg p-2 relative hover:border-gray-500 transition-colors"
                                    wire:dblclick="openPhotoForm({{ $index }})">
                                    <!-- Imagen con tamaño adaptable -->
                                    <div class="photo-image-container">
                                        <img src="{{ $photo['preview'] }}" alt="Foto del servicio"
                                            class="photo-image" />
                                    </div>

                                    <!-- Botón eliminar -->
                                    <button wire:click="deletePhoto({{ $index }})"
                                        class="delete-photo-btn absolute top-1 right-1 bg-red-600 hover:bg-red-700 text-white rounded-full w-5 h-5 flex items-center justify-center text-xs transition-all duration-200 z-10 shadow-lg"
                                        title="Eliminar foto">
                                        &times;
                                    </button>

                                    <!-- Descripción -->
                                    <div class="w-full text-center px-1">
                                        <div
                                            class="text-gray-300 text-xs mb-1 min-h-[14px] flex items-center justify-center">
                                            <span
                                                class="{{ empty($photo['description']) ? 'text-gray-400 italic' : '' }} truncate max-w-full">
                                                {{ $photo['description'] ?: 'Agregar descripción' }}
                                            </span>
                                        </div>

                                        <!-- Botón editar -->
                                        <button type="button" wire:click="openPhotoForm({{ $index }})"
                                            class="edit-photo-btn text-blue-400 hover:text-blue-300 transition-colors p-1 rounded"
                                            title="Editar foto">
                                            <x-lucide name="edit" class="w-3 h-3" />
                                        </button>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <!-- Mensaje cuando no hay fotos -->
                        <div class="no-photos-message text-center py-8 text-gray-400">
                            <x-lucide name="image" class="w-12 h-12 mx-auto mb-3 text-gray-600" />
                            <p class="text-sm">No hay fotos agregadas</p>
                            <p class="text-xs text-gray-500 mt-1">Usa el botón "Agregar foto" para comenzar</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    {{-- ========================================================================================================================================================================================================= --}}
    {{-- ========================================================================================================================================================================================================= --}}
    {{-- //============================================================================================================================================================================== --}}
    <!-- Modal de Selección de Usuario -->
    @if ($showModal)
        <div class="app-modal service-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-full max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white">
                            Selección de Usuario
                            <div class="text-xs text-gray-400 font-normal mt-1">
                                {{ $modalType ?? '' }},
                                {{ $modalParam1 ?? '' }},
                                {{ $modalParam2 ?? '' }},
                                {{ $modalParam4 ?? '' }},
                            </div>
                        </h3>
                        <button wire:click="closeModal" class="modal-action text-white transition-colors bg-gray-600 hover:bg-gray-700">
                            <x-lucide name="x" class="w-6 h-6" />
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Campos de búsqueda -->
                    <div class="mb-4">
                        <div class="flex items-center justify-between mb-3">
                            <h4 class="text-sm font-medium text-gray-300">Filtros de búsqueda</h4>
                            @if($userSearchName || $userSearchPosition || $userSearchDirection || $userSearchLvl)
                                <button wire:click="clearUserFilters" 
                                    class="modal-action text-white text-xs   transition-colors flex items-center gap-1 bg-blue-600 hover:bg-blue-700">
                                    <x-lucide name="x" class="w-3 h-3" />
                                    Limpiar filtros
                                </button>
                            @endif
                        </div>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por Nombre
                                </label>
                                <input wire:model.live="userSearchName" type="text" placeholder="Nombre..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por Posición
                                </label>
                                <input wire:model.live="userSearchPosition" type="text" placeholder="Posición..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por Dirección
                                </label>
                                <input wire:model.live="userSearchDirection" type="text"
                                    placeholder="Dirección..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por Nivel
                                </label>
                                <input wire:model.live="userSearchLvl" type="text" placeholder="Nivel..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                        @if($userSearchName || $userSearchPosition || $userSearchDirection || $userSearchLvl)
                            <div class="mt-2 text-xs text-gray-400 flex items-center gap-2">
                                <x-lucide name="search" class="w-3 h-3" />
                                <span>Mostrando {{ $filteredUsers->count() }} usuario(s) de {{ $users->count() }} total</span>
                            </div>
                        @endif
                    </div>
                    <!-- Tabla de usuarios -->
                    <div class="bg-gray-700 rounded-3xl overflow-hidden border border-gray-600">
                        <div class="min-w-0 w-full max-w-full">
                            <table class="responsive-table w-full rounded-3xl overflow-hidden">
                                <thead class="bg-gray-600">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs rounded-tl-3xl">
                                            Nombre
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs">
                                            RFC
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs">
                                            Posición
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs">
                                            Dirección
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs">
                                            Sexo
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs">
                                            Tipo
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs">
                                            Nivel
                                        </th>
                                        <th
                                            class="px-4 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider text-xs rounded-tr-3xl">
                                            Acciones
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-700 divide-y divide-gray-600">
                                    @forelse($filteredUsers as $user)
                                        {{-- para que el el regitro del modal pueda funciona es obligatori usar el wire:key --}}
                                        <tr wire:key="user-{{ $user->id }}"
                                            class="hover:bg-gray-600 transition-colors">
                                            <td data-label="Nombre" class="px-4 py-4 whitespace-normal text-xs text-gray-300">
                                                <div class="font-medium text-white text-xs">{{ $user->name }}</div>
                                            </td>
                                            <td data-label="RFC" class="px-4 py-4 whitespace-normal text-xs text-gray-300">
                                                {{ $user->rfc ?? 'N/A' }}
                                            </td>
                                            <td data-label="Posición" class="px-4 py-4 whitespace-normal text-xs text-gray-300">
                                                {{ $user->position ?? 'N/A' }}
                                            </td>
                                            <td data-label="Dirección" class="px-4 py-4 whitespace-normal text-xs text-gray-300">
                                                {{ $user->direction ?? 'N/A' }}
                                            </td>
                                            <td data-label="Sexo" class="px-4 py-4 whitespace-normal text-xs text-gray-300">
                                                {{ $user->sex ?? 'N/A' }}
                                            </td>
                                            <td data-label="Tipo" class="px-4 py-4 whitespace-normal text-xs text-gray-300">
                                                {{ $user->tipo ?? 'N/A' }}
                                            </td>
                                            <td data-label="Nivel" class="px-4 py-4 whitespace-normal text-xs text-gray-300">
                                                {{ $user->lvl ?? 'N/A' }}
                                            </td>
                                            <td data-label="Acciones" class="px-4 py-4 whitespace-normal text-xs font-medium">
                                                <button
                                                    wire:click="selectUser({{ $user->id }}, @js($user->name))"
                                                    class="modal-action bg-blue-600 text-white hover:bg-blue-700 transition-colors"
                                                    title="Seleccionar">
                                                    <i class="ri-shield-user-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="px-6 py-12 text-center text-gray-400">
                                                <x-lucide name="user-x"
                                                    class="w-12 h-12 mx-auto mb-4 text-gray-600" />
                                                <p class="text-lg">No se encontraron usuarios</p>
                                                <p class="text-sm">
                                                    @if($userSearchName || $userSearchPosition || $userSearchDirection || $userSearchLvl)
                                                        Intenta con otros términos de búsqueda o 
                                                        <button wire:click="clearUserFilters" class="modal-action text-white underline bg-blue-600 hover:bg-blue-700">
                                                            limpia los filtros
                                                        </button>
                                                    @else
                                                        No hay usuarios disponibles
                                                    @endif
                                                </p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Botones del modal -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-700 mt-4">
                        <button type="button" wire:click="closeModal"
                            class="modal-action text-white px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif
    {{-- //============================================================================================================================================================================== --}}
    {{-- //============================================================================================================================================================================== --}}
    {{-- ========================================================================================================================================================================================================= --}}
    @if ($showInventoryModal)
        <div class="app-modal service-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-7xl max-h-[90vh] overflow-y-auto">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white">
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
                        <button wire:click="closeInventoryModal"
                            class="modal-action text-white transition-colors bg-gray-600 hover:bg-gray-700">
                            <x-lucide name="x" class="w-6 h-6" />
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    <!-- Campos de búsqueda para inventario -->
                    <div class="mb-4">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por NI
                                </label>
                                <input wire:model.live="inventorySearchNi" type="text" placeholder="NI..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por SN
                                </label>
                                <input wire:model.live="inventorySearchSn" type="text" placeholder="SN..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por TYPE
                                </label>
                                <input wire:model.live="inventorySearchType" type="text" placeholder="TYPE..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por ARTICULO
                                </label>
                                <input wire:model.live="inventorySearchArticulo" type="text"
                                    placeholder="ARTICULO..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por Nombre Usuario
                                </label>
                                <input wire:model.live="inventorySearchUserName" type="text"
                                    placeholder="Nombre usuario..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-300 mb-2">
                                    Buscar por Dirección Usuario
                                </label>
                                <input wire:model.live="inventorySearchUserDirection" type="text"
                                    placeholder="Dirección usuario..."
                                    class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>
                    <!-- Tabla de inventarios -->
                    <div class="bg-gray-700 rounded-3xl overflow-hidden border border-gray-600">
                        <div class="min-w-0 w-full max-w-full">
                            <table class="responsive-table w-full rounded-3xl overflow-hidden">
                                <thead class="bg-gray-600">
                                    <tr>
                                        <th
                                            class="px-4 py-3 text-left text-[11px] font-medium text-gray-300 uppercase tracking-wider">
                                            NI / SN</th>
                                        <th
                                            class="px-4 py-3 text-left text-[11px] font-medium text-gray-300 uppercase tracking-wider">
                                            TYPE</th>
                                        <th
                                            class="px-4 py-3 text-left text-[11px] font-medium text-gray-300 uppercase tracking-wider">
                                            ARTICULO</th>
                                        <th
                                            class="px-4 py-3 text-left text-[11px] font-medium text-gray-300 uppercase tracking-wider">
                                            USUARIO / DIRECCIÓN</th>
                                        <th
                                            class="px-4 py-3 text-left text-[11px] font-medium text-gray-300 uppercase tracking-wider">
                                            Acciones</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-gray-700 divide-y divide-gray-600">
                                    @forelse($filteredInventories as $inv)
                                        <tr wire:key="inv-{{ $inv->id }}"
                                            class="hover:bg-gray-600 transition-colors">
                                            <td data-label="NI / SN" class="px-4 py-4 whitespace-normal text-[11px] text-gray-300">
                                                <div class="flex flex-col space-y-1">
                                                    <div><span class="text-blue-400 font-medium">NI:</span>
                                                        {{ $inv->ni ?? 'N/A' }}</div>
                                                    <div><span class="text-green-400 font-medium">SN:</span>
                                                        {{ $inv->ns ?? 'N/A' }}</div>
                                                </div>
                                            </td>
                                            <td data-label="TYPE" class="px-4 py-4 whitespace-normal text-[11px] text-gray-300">
                                                {{ $inv->type ?? 'N/A' }}</td>
                                            <td data-label="ARTICULO" class="px-4 py-4 whitespace-normal text-[11px] text-gray-300">
                                                {{ $inv->articulo ?? 'N/A' }}</td>
                                            <td data-label="USUARIO / DIRECCIÓN" class="px-4 py-4 whitespace-normal text-[11px] text-gray-300">
                                                @if ($inv->assignedUser)
                                                    <div class="flex flex-col space-y-1">
                                                        <div><span class="text-blue-400 font-medium">Usuario:</span>
                                                            {{ $inv->assignedUser->name }}</div>
                                                        <div><span
                                                                class="text-[10px] text-gray-400">{{ $inv->assignedUser->position ?? 'N/A' }}</span>
                                                        </div>
                                                        <div><span class="text-green-400 font-medium">Dirección:</span>
                                                            {{ $inv->assignedUser->direction ?? 'N/A' }}</div>
                                                    </div>
                                                @elseif($inv->responsible)
                                                    <div class="flex flex-col space-y-1">
                                                        <div><span class="text-blue-400 font-medium">Usuario:</span>
                                                            {{ $inv->responsible->name }}</div>
                                                        <div><span
                                                                class="text-[10px] text-gray-400">{{ $inv->responsible->position ?? 'N/A' }}</span>
                                                        </div>
                                                        <div><span class="text-green-400 font-medium">Dirección:</span>
                                                            {{ $inv->responsible->direction ?? 'N/A' }}</div>
                                                    </div>
                                                @else
                                                    <div class="flex flex-col space-y-1">
                                                        <div><span class="text-blue-400 font-medium">Usuario:</span>
                                                            <span class="text-gray-500">Sin usuario</span></div>
                                                        <div><span class="text-green-400 font-medium">Dirección:</span>
                                                            <span class="text-gray-500">N/A</span></div>
                                                    </div>
                                                @endif
                                            </td>
                                            <td data-label="Acciones" class="px-4 py-4 whitespace-normal text-[11px] font-medium">
                                                <button wire:click="selectInventory({{ $inv->id }})"
                                                    class="modal-action bg-green-600 text-white hover:bg-green-700 transition-colors"
                                                    title="Seleccionar">
                                                    <i class="ri-invision-line"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                                                <x-lucide name="package-x"
                                                    class="w-12 h-12 mx-auto mb-4 text-gray-600" />
                                                <p class="text-lg">No se encontraron inventarios</p>
                                                <p class="text-sm">Intenta con otros términos de búsqueda</p>
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <!-- Botones del modal -->
                    <div class="flex justify-end gap-3 pt-4 border-t border-gray-700 mt-4">
                        <button type="button" wire:click="closeInventoryModal"
                            class="modal-action text-white px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                            Cancelar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <!-- Modal para seleccionar inventarios para asociar al servicio -->
    @if ($showInventorySelection)
        <div class="app-modal service-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-5xl max-h-[80vh] overflow-y-auto">
                <div class="p-4 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-base font-medium text-white">
                            Seleccionar Inventarios
                        </h3>
                        <button wire:click="closeInventorySelection"
                            class="modal-action text-white transition-colors bg-gray-600 hover:bg-gray-700">
                            <x-lucide name="x" class="w-5 h-5" />
                        </button>
                    </div>
                </div>
                <div class="p-4">
                    <!-- Campos de búsqueda para inventario -->
                    <div class="mb-3">
                        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6 gap-2">
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">
                                    Buscar por NI
                                </label>
                                <input wire:model.live="inventorySearchNi" type="text" placeholder="NI..."
                                    class="w-full px-2 py-1 text-xs bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">
                                    Buscar por SN
                                </label>
                                <input wire:model.live="inventorySearchSn" type="text" placeholder="SN..."
                                    class="w-full px-2 py-1 text-xs bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">
                                    Buscar por TYPE
                                </label>
                                <input wire:model.live="inventorySearchType" type="text" placeholder="TYPE..."
                                    class="w-full px-2 py-1 text-xs bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">
                                    Buscar por ARTICULO
                                </label>
                                <input wire:model.live="inventorySearchArticulo" type="text" placeholder="ARTICULO..."
                                    class="w-full px-2 py-1 text-xs bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">
                                    Buscar por Usuario
                                </label>
                                <input wire:model.live="inventorySearchUserName" type="text" placeholder="Usuario..."
                                    class="w-full px-2 py-1 text-xs bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-300 mb-1">
                                    Buscar por Dirección
                                </label>
                                <input wire:model.live="inventorySearchUserDirection" type="text" placeholder="Dirección..."
                                    class="w-full px-2 py-1 text-xs bg-gray-700 border border-gray-600 rounded text-white placeholder-gray-400 focus:ring-1 focus:ring-blue-500 focus:border-transparent">
                            </div>
                        </div>
                    </div>

                    <!-- Tabla de inventarios -->
                    <div class="min-w-0 w-full max-w-full">
                        <table class="responsive-table w-full divide-y divide-gray-600">
                            <thead class="bg-gray-700">
                                <tr>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        NI / Seleccionar
                                    </th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        SN
                                    </th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Artículo
                                    </th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Marca/Modelo
                                    </th>
                                    <th class="px-2 py-2 text-left text-xs font-medium text-gray-300 uppercase tracking-wider">
                                        Usuario
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-gray-800 divide-y divide-gray-600">
                                @forelse ($filteredInventories as $inv)
                                    <tr class="hover:bg-gray-700 transition-colors">
                                        <td data-label="NI / Seleccionar" class="px-2 py-2 whitespace-normal text-xs text-white">
                                            <div class="flex items-center gap-2">
                                                <span>{{ $inv->ni ?? 'N/A' }}</span>
                                                @if(in_array($inv->id, $selectedInventories))
                                                    <span class="text-green-400 text-xs font-bold">✓ Seleccionado</span>
                                                @else
                                                    <button wire:click="addInventoryToService({{ $inv->id }})"
                                                        class="modal-action bg-blue-500 text-white px-2 py-1 rounded text-xs font-bold hover:bg-blue-600 transition-colors flex items-center justify-center"
                                                        title="Agregar al servicio">
                                                        <x-lucide name="plus" class="w-3 h-3 text-black" />
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                        <td data-label="SN" class="px-2 py-2 whitespace-normal text-xs text-white">
                                            {{ $inv->ns ?? 'N/A' }}
                                        </td>
                                        <td data-label="Artículo" class="px-2 py-2 whitespace-normal text-xs text-white">
                                            {{ $inv->articulo ?? 'N/A' }}
                                        </td>
                                        <td data-label="Marca/Modelo" class="px-2 py-2 whitespace-normal text-xs text-white">
                                            {{ $inv->marca ?? 'N/A' }} / {{ $inv->modelo ?? 'N/A' }}
                                        </td>
                                        <td data-label="Usuario" class="px-2 py-2 whitespace-normal text-xs text-white">
                                            @if ($inv->assignedUser)
                                                <div class="space-y-1">
                                                    <div><span class="text-blue-400 font-medium">Usuario:</span>
                                                        <span class="text-gray-300">{{ $inv->assignedUser->name }}</span></div>
                                                    <div><span class="text-green-400 font-medium">Dirección:</span>
                                                        <span class="text-gray-300">{{ $inv->assignedUser->direction }}</span></div>
                                                </div>
                                            @else
                                                <div class="space-y-1">
                                                    <div><span class="text-blue-400 font-medium">Usuario:</span>
                                                        <span class="text-gray-500">Sin usuario</span></div>
                                                    <div><span class="text-green-400 font-medium">Dirección:</span>
                                                        <span class="text-gray-500">N/A</span></div>
                                                </div>
                                            @endif
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="px-6 py-8 text-center text-gray-400">
                                            <x-lucide name="package-x"
                                                class="w-8 h-8 mx-auto mb-2 text-gray-600" />
                                            <p class="text-sm">No se encontraron inventarios</p>
                                            <p class="text-xs">Intenta con otros términos de búsqueda</p>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <!-- Botones del modal -->
                    <div class="flex justify-end gap-3 pt-3 border-t border-gray-700 mt-3">
                        <button type="button" wire:click="closeInventorySelection"
                            class="modal-action text-white px-3 py-1 text-xs  bg-gray-700 hover:bg-gray-600 rounded transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- //============================================================================================================================================================================== --}}
    {{-- ========================================================================================================================================================================================================= --}}

    <!-- Modal para agregar/editar fotos -->
    @if ($showPhotoForm)
        <div class="app-modal service-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
            <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white">
                            @if ($editingPhotoIndex !== null)
                                Editar descripción de la foto
                            @else
                                Agregar Foto al Servicio
                            @endif
                        </h3>
                        <button
                            @if ($editingPhotoIndex !== null) wire:click="cancelPhotoDescriptionEdit"
                            @else
                                wire:click="closePhotoForm" @endif
                            class="modal-action text-white transition-colors bg-gray-600 hover:bg-gray-700">
                            <x-lucide name="x" class="w-6 h-6" />
                        </button>
                    </div>
                </div>
                <div class="p-6">
                    @if ($editingPhotoIndex !== null)
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Cambiar imagen (opcional)
                            </label>
                            <input type="file" wire:model="modalPhoto"
                                class="w-full text-gray-100 bg-gray-700 rounded p-2 border border-gray-600"
                                accept="image/*">
                            @error('modalPhoto')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Descripción
                            </label>
                            <input type="text" wire:model="modalPhotoDescription"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Descripción de la foto...">
                        </div>
                        <div class="mb-4">
                            <img src="{{ $modalPhotoPreview }}" alt="Preview" class="max-h-40 mx-auto rounded">
                        </div>
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-700">
                            <button type="button" wire:click="cancelPhotoDescriptionEdit"
                                class="modal-action text-white px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="button" wire:click="savePhotoDescriptionEdit"
                                class="modal-action text-white px-4 py-2 bg-green-600 hover:bg-green-700  rounded-lg transition-colors flex items-center gap-2">
                                <x-lucide name="save" class="w-4 h-4" />
                                Guardar
                            </button>
                        </div>
                    @else
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Seleccionar imagen
                            </label>
                            <input type="file" wire:model="modalPhoto"
                                class="w-full text-gray-100 bg-gray-700 rounded p-2 border border-gray-600"
                                accept="image/*">
                            @error('modalPhoto')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-300 mb-2">
                                Descripción (opcional)
                            </label>
                            <input type="text" wire:model="modalPhotoDescription"
                                class="w-full px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Descripción de la foto...">
                            @error('modalPhotoDescription')
                                <span class="text-red-400 text-sm">{{ $message }}</span>
                            @enderror
                        </div>
                        @if ($modalPhotoPreview)
                            <div class="mb-4">
                                <img src="{{ $modalPhotoPreview }}" alt="Preview" class="max-h-40 mx-auto rounded">
                            </div>
                        @endif
                        <div class="flex justify-end gap-3 pt-4 border-t border-gray-700">
                            <button type="button" wire:click="closePhotoForm"
                                class="modal-action text-white px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors">
                                Cancelar
                            </button>
                            <button type="button" wire:click="addPhoto"
                                class="modal-action text-white px-4 py-2 bg-green-600 hover:bg-green-700  rounded-lg transition-colors flex items-center gap-2">
                                <x-lucide name="plus" class="w-4 h-4" />
                                Agregar Foto
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    <style>
        /* Estilos para el grid de fotos responsive */
        .photos-grid {
            display: grid;
            gap: 1rem;
            grid-template-columns: repeat(auto-fill, minmax(120px, 1fr));
            align-items: start;
            justify-items: center;
        }

        /* Ajustes para diferentes tamaños de pantalla */
        @media (min-width: 480px) {
            .photos-grid {
                grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            }
        }

        @media (min-width: 640px) {
            .photos-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            }
        }

        @media (min-width: 768px) {
            .photos-grid {
                grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .photos-grid {
                grid-template-columns: repeat(auto-fill, minmax(160px, 1fr));
            }
        }

        /* Mejoras para las tarjetas de fotos */
        .photo-card {
            transition: all 0.2s ease-in-out;
            cursor: pointer;
        }

        .photo-card:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.3);
        }

        /* Contenedor de imagen adaptable */
        .photo-image-container {
            position: relative;
            overflow: hidden;
            border-radius: 0.375rem;
            width: 100%;
            height: 80px;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #000;
            margin-bottom: 0.5rem;
        }

        .photo-image {
            transition: transform 0.2s ease-in-out;
            max-width: 100%;
            max-height: 100%;
            width: auto;
            height: auto;
            object-fit: contain;
        }

        .photo-card:hover .photo-image {
            transform: scale(1.05);
        }

        /* Mejorar el tamaño de las tarjetas */
        .photo-card {
            width: 100%;
            min-width: 120px;
            max-width: 200px;
            height: auto;
            min-height: 140px;
        }

        /* Asegurar que las imágenes se vean bien */
        .photo-image-container img {
            border-radius: 0.25rem;
        }

        /* Mejorar la responsividad del grid */
        @media (max-width: 480px) {
            .photos-grid {
                grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
                gap: 0.5rem;
            }

            .photo-card {
                min-width: 100px;
                max-width: 150px;
            }

            .photo-image-container {
                height: 60px;
            }
        }

        /* Estilos para el botón agregar foto fijo */
        .add-photo-btn {
            position: sticky;
            top: 0;
            z-index: 10;
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            border: 1px solid #047857;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .add-photo-btn:hover {
            background: linear-gradient(135deg, #047857 0%, #065f46 100%);
            border-color: #065f46;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        /* Mejorar el mensaje cuando no hay fotos */
        .no-photos-message {
            background: linear-gradient(135deg, #374151 0%, #4b5563 100%);
            border: 1px solid #6b7280;
            border-radius: 0.5rem;
            margin: 1rem 0;
        }

        /* Mejoras para el botón de eliminar */
        .delete-photo-btn {
            opacity: 0.8;
            transition: all 0.2s ease-in-out;
        }

        .delete-photo-btn:hover {
            opacity: 1;
            transform: scale(1.1);
        }

        /* Mejoras para el botón de editar */
        .edit-photo-btn {
            transition: all 0.2s ease-in-out;
        }

        .edit-photo-btn:hover {
            transform: scale(1.1);
            background-color: rgba(59, 130, 246, 0.1);
        }
    </style>

    <script>
        document.addEventListener('livewire:init', () => {
            // Manejo de eventos de actualización de textarea
            Livewire.on('update-textarea', (event) => {
                const field = event.field;
                const value = event.value;
                const textarea = document.getElementById(field);
                if (textarea) {
                    textarea.value = value;
                    // Disparar evento para que Livewire detecte el cambio
                    textarea.dispatchEvent(new Event('input', {
                        bubbles: true
                    }));
                    console.log(`Textarea ${field} actualizado con valor:`, value);
                } else {
                    console.warn(`Textarea con id '${field}' no encontrado`);
                }
            });

            // Manejo de errores de Livewire
            Livewire.on('error', (error) => {
                console.error('Error de Livewire:', error);
                // Mostrar notificación de error
                showNotification('Error: ' + error.message, 'error');
            });

            // Manejo de errores de validación
            Livewire.on('validation-error', (errors) => {
                console.error('Errores de validación:', errors);
                // Mostrar notificación de error de validación
                const errorMessages = Object.values(errors).flat();
                showNotification('Errores de validación: ' + errorMessages.join(', '), 'error');
            });

            // Función para mostrar notificaciones
            function showNotification(message, type = 'info') {
                const notification = document.createElement('div');
                notification.className = `fixed top-4 right-4 p-4 rounded-lg shadow-lg z-50 ${
                    type === 'error' ? 'bg-red-600 text-white' : 
                    type === 'success' ? 'bg-green-600 text-white' : 
                    'bg-blue-600 text-white'
                }`;
                notification.innerHTML = `
                    <div class="flex items-center gap-2">
                        <span data-notification-message></span>
                        <button onclick="this.parentElement.parentElement.remove()" class="ml-2">×</button>
                    </div>
                `;
                notification.querySelector('[data-notification-message]').textContent = message;
                document.body.appendChild(notification);
                
                // Auto-remover después de 5 segundos
                setTimeout(() => {
                    if (notification.parentElement) {
                        notification.remove();
                    }
                }, 5000);
            }

            // Debugging para el modal de inventario
            document.addEventListener('click', (e) => {
                if (e.target.matches('[wire\\:click*="selectInventory"]')) {
                    console.log('Botón selectInventory clickeado:', e.target);
                }
            });

            // Debugging para subida de archivos
            document.addEventListener('change', (e) => {
                if (e.target.type === 'file') {
                    const file = e.target.files[0];
                    if (file) {
                        console.log('Archivo seleccionado:', {
                            name: file.name,
                            size: file.size,
                            type: file.type,
                            lastModified: new Date(file.lastModified)
                        });
                        
                        // Verificar tamaño del archivo
                        if (file.size > 2 * 1024 * 1024) { // 2MB
                            showNotification('El archivo es demasiado grande. Máximo 2MB permitido.', 'error');
                            e.target.value = ''; // Limpiar input
                        }
                        
                        // Verificar tipo de archivo
                        const allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                        if (!allowedTypes.includes(file.type)) {
                            showNotification('Tipo de archivo no permitido. Solo se permiten imágenes.', 'error');
                            e.target.value = ''; // Limpiar input
                        }
                    }
                }
            });
        });

        // Función global para debugging
        window.debugServiceEdit = function() {
            console.log('=== DEBUG SERVICE EDIT ===');
            console.log('Componente Livewire:', Livewire.find('service-edit'));
            console.log('Elementos del DOM:', {
                modalInventory: document.querySelector('[wire\\:click*="openInventoryModal"]'),
                photoForm: document.querySelector('[wire\\:click*="openPhotoForm"]'),
                textareas: document.querySelectorAll('textarea')
            });
        };
    </script>

</div>
