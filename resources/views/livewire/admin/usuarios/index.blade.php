<div>
    <!-- Mensaje de éxito -->
    @if (session()->has('message'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)" 
            x-show="show"
            x-transition
            class="mb-4 p-4 bg-green-600 text-white rounded-lg flex items-center justify-between"
        >
            <span>{{ session('message') }}</span>
            <button type="button" class="text-white hover:text-gray-200" @click="show = false">
                <x-lucide name="x" class="w-5 h-5" />
            </button>
        </div>
    @endif

    <!-- Mensaje de error -->
    @if (session()->has('error'))
        <div 
            x-data="{ show: true }" 
            x-init="setTimeout(() => show = false, 5000)" 
            x-show="show"
            x-transition
            class="mb-4 p-4 bg-red-600 text-white rounded-lg flex items-center justify-between"
        >
            <span>{{ session('error') }}</span>
            <button type="button" class="text-white hover:text-gray-200" @click="show = false">
                <x-lucide name="x" class="w-5 h-5" />
            </button>
        </div>
    @endif

    <!-- Barra de búsqueda y botón agregar -->
    <div class="flex flex-col sm:flex-row gap-4 mb-4">
        <div class="flex-1">
            <div class="relative">
                <x-lucide name="search" class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" />
                <input 
                    wire:model.live="search" 
                    type="text" 
                    placeholder="Buscar usuarios..." 
                    class="w-full pl-10 pr-4 py-3 bg-gray-800 border border-gray-700 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base mobile-text"
                >
            </div>
        </div>
        <div class="flex-shrink-0">
            <button 
                wire:click="openModal"
                class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg flex items-center justify-center gap-2 transition-colors text-base mobile-touch-target"
            >
                <x-lucide name="plus" class="w-5 h-5" />
                <span class="hidden sm:inline">Agregar usuario</span>
                <span class="sm:hidden">Agregar</span>
            </button>
        </div>
    </div>

    <!-- Tabla de usuarios -->
    <div class="bg-gray-800 rounded-3xl overflow-hidden shadow-lg border border-gray-700 w-full max-w-none">
        <!-- Vista móvil: Cards -->
        <div class="block lg:hidden">
            @forelse($users as $user)
                <div class="p-4 border-b border-gray-700 hover:bg-gray-700 transition-colors mobile-card">
                    <div class="flex items-start justify-between mb-3">
                        <div class="flex items-center flex-1">
                            <div class="flex-shrink-0 h-12 w-12 mr-3">
                                @if($user->hasProfilePhoto())
                                    <img class="h-12 w-12 rounded-full object-cover" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                @else
                                    <div class="h-12 w-12 rounded-full bg-gray-600 flex items-center justify-center">
                                        <span class="text-sm font-medium text-white">{{ $user->initials }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="text-sm font-medium text-white truncate">{{ $user->name }}</div>
                                <div class="text-xs text-gray-400 truncate">{{ $user->email }}</div>
                                <div class="text-xs text-gray-500">ID: {{ $user->id }}</div>
                            </div>
                        </div>
                        <div class="flex flex-col gap-1">
                            <button 
                                wire:click="editUser({{ $user->id }})"
                                class="text-blue-400 hover:text-blue-300 transition-colors p-1"
                                title="Editar"
                            >
                                <x-lucide name="edit" class="w-4 h-4" />
                            </button>
                            <button 
                                wire:click="deleteUser({{ $user->id }})"
                                onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')"
                                class="text-red-400 hover:text-red-300 transition-colors p-1"
                                title="Eliminar"
                            >
                                <x-lucide name="trash-2" class="w-4 h-4" />
                            </button>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-2 text-xs">
                        <div>
                            <span class="text-gray-400">RFC:</span>
                            <span class="text-white">{{ $user->rfc ?? 'N/A' }}</span>
                        </div>
                        <div>
                            <span class="text-gray-400">Nivel:</span>
                            <span class="text-white">{{ $user->lvl ?? 'N/A' }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-400">Dirección:</span>
                            <span class="text-white truncate block">{{ $user->direction ?? 'N/A' }}</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-400">Posición:</span>
                            <span class="text-white truncate block">{{ $user->position ?? 'N/A' }}</span>
                        </div>
                    </div>
                    
                    <div class="flex flex-wrap gap-2 mt-3">
                        @if($user->sex)
                            <button 
                                wire:click="toggleSex({{ $user->id }})"
                                class="flex items-center gap-1 px-2 py-1 rounded-full text-xs font-medium transition-colors {{ $user->sex === 'masculino' ? 'bg-blue-100 text-black' : 'bg-pink-100 text-black' }}"
                            >
                                <x-lucide name="{{ $user->sex === 'masculino' ? 'male' : 'female' }}" class="w-3 h-3" />
                                {{ ucfirst($user->sex) }}
                            </button>
                        @else
                            <button 
                                wire:click="toggleSex({{ $user->id }})"
                                class="flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 text-black text-xs font-medium"
                            >
                                <x-lucide name="male" class="w-3 h-3" />
                                Establecer
                            </button>
                        @endif
                        
                        @switch($user->tipo)
                            @case(1)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                    Admin
                                </span>
                                @break
                            @case(2)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                    Técnico
                                </span>
                                @break
                            @case(3)
                                <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                    Usuario
                                </span>
                                @break
                            @default
                                <span class="text-gray-500 text-xs">N/A</span>
                        @endswitch
                        
                        <button 
                            wire:click="toggleStatus({{ $user->id }})"
                            class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium transition-colors {{ $user->status ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-black' }}"
                        >
                            {{ $user->status ? 'Activo' : 'Inactivo' }}
                        </button>
                    </div>
                    
                    <!-- Acciones móviles -->
                    <div class="flex justify-end gap-2 mt-3 pt-3 border-t border-gray-700">
                        <button 
                            wire:click="editUser({{ $user->id }})"
                            class="flex items-center gap-1 px-3 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-xs transition-colors"
                        >
                            <x-lucide name="edit" class="w-3 h-3" />
                            Editar
                        </button>
                        <button 
                            wire:click="deleteUser({{ $user->id }})"
                            onclick="return confirm('¿Estás seguro de que quieres eliminar este usuario?')"
                            class="flex items-center gap-1 px-3 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg text-xs transition-colors"
                        >
                            <x-lucide name="trash-2" class="w-3 h-3" />
                            Eliminar
                        </button>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400">
                    <x-lucide name="users" class="w-12 h-12 mx-auto mb-4 text-gray-600" />
                    <p class="text-lg">No se encontraron usuarios</p>

                    <p class="text-sm">Comienza agregando un nuevo usuario</p>
                </div>
            @endforelse
        </div>
        
        <!-- Vista desktop: Tabla -->
        <div class="hidden lg:block overflow-x-auto w-full">
            <table class="w-full divide-y divide-gray-700 rounded-3xl overflow-hidden table-auto-height">
                <thead class="bg-gray-700">
                    <tr>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[25%] rounded-tl-3xl">
                            Usuario
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[10%]">
                            RFC
                        </th>
                        <th class="px-3 py-3 text-left text-xs font-medium text-gray-300 uppercase tracking-wider w-[20%]">
                            Dirección / Posición
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[10%]">
                            Sexo
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[8%]">
                            Nivel
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[12%]">
                            Tipo
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[8%]">
                            Estado
                        </th>
                        <th class="px-3 py-3 text-center text-xs font-medium text-gray-300 uppercase tracking-wider w-[7%] rounded-tr-3xl">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse($users as $user)
                        <tr class="hover:bg-gray-700 transition-colors">
                            <td class="px-3 py-3">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0 h-6 w-6 mt-1">
                                        @if($user->hasProfilePhoto())
                                            <img class="h-6 w-6 rounded-full object-cover" src="{{ $user->profile_photo_url }}" 
                                            alt="{{ $user->name }}">
                                        @else
                                            <div class="h-6 w-6 rounded-full bg-gray-600 flex items-center justify-center">
                                                <span class="text-xs font-medium text-white">{{ $user->initials }}</span>
                                            </div>
                                        @endif
                                    </div>
                                    <div class="ml-3 min-w-0 flex-1">
                                        <div class="text-xs font-medium text-white truncate" title="ID: {{ $user->id }}">ID: {{ $user->id }}</div>
                                        <div class="text-sm font-medium text-white truncate" title="{{ $user->name }}">{{ $user->name }}</div>
                                        <div class="text-sm text-gray-400 truncate" title="{{ $user->email }}">{{ $user->email }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-300">
                                <div class="truncate" title="{{ $user->rfc ?? 'N/A' }}">
                                    {{ $user->rfc ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-3 py-3 text-sm text-gray-300">
                                <div class="space-y-1">
                                    @if($user->direction)
                                        <div class="font-medium text-white truncate" title="{{ $user->direction }}">
                                            {{ Str::limit($user->direction, 30) }}
                                        </div>
                                    @endif
                                    @if($user->position)
                                        <div class="text-gray-400 text-xs truncate" title="{{ $user->position }}">
                                            {{ Str::limit($user->position, 30) }}
                                        </div>
                                    @endif
                                    @if(!$user->direction && !$user->position)
                                        <span class="text-gray-500">N/A</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($user->sex)
                                    <div class="flex items-center justify-center gap-2">
                                        @if($user->sex === 'masculino')
                                            <button 
                                                wire:click="toggleSex({{ $user->id }})"
                                                class="flex items-center gap-1 px-2 py-1 rounded-full bg-blue-300 hover:bg-blue-200 transition-colors cursor-pointer"
                                                title="Haz clic para cambiar a Femenino"
                                            >
                                                <x-lucide name="male" class="w-3 h-3 text-black" />
                                                <span class="text-xs font-medium text-black">Mas</span>
                                            </button>
                                        @else
                                            <button 
                                                wire:click="toggleSex({{ $user->id }})"
                                                class="flex items-center gap-1 px-2 py-1 rounded-full bg-pink-300 hover:bg-pink-200 transition-colors cursor-pointer"
                                                title="Haz clic para cambiar a Masculino"
                                            >
                                                <x-lucide name="female" class="w-3 h-3 text-black" />
                                                <span class="text-xs font-medium text-black">Fem</span>
                                            </button>
                                        @endif
                                    </div>
                                @else
                                    <button 
                                        wire:click="toggleSex({{ $user->id }})"
                                        class="flex items-center gap-1 px-2 py-1 rounded-full bg-gray-100 hover:bg-gray-200 transition-colors cursor-pointer"
                                        title="Haz clic para establecer como Masculino"
                                    >
                                        <x-lucide name="male" class="w-3 h-3 text-black" />
                                        <span class="text-xs font-medium text-black">?</span>
                                    </button>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center text-sm text-gray-300">
                                <div class="truncate" title="Nivel: {{ $user->lvl ?? 'N/A' }}">
                                    {{ $user->lvl ?? 'N/A' }}
                                </div>
                            </td>
                            <td class="px-3 py-3 text-center">
                                @switch($user->tipo)
                                    @case(1)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800" title="Administrador">
                                            Admin
                                        </span>
                                        @break
                                    @case(2)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800" title="En Servicio Técnico">
                                            Técnico
                                        </span>
                                        @break
                                    @case(3)
                                        <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800" title="Usuario">
                                            Usuario
                                        </span>
                                        @break
                                    @default
                                        <span class="text-gray-500">N/A</span>
                                @endswitch
                            </td>
                            <td class="px-3 py-3 text-center">
                                @if($user->status)
                                    <button 
                                        wire:click="toggleStatus({{ $user->id }})"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 hover:bg-green-200 transition-colors cursor-pointer"
                                        title="Haz clic para desactivar"
                                    >
                                        Activo
                                    </button>
                                @else
                                    <button 
                                        wire:click="toggleStatus({{ $user->id }})"
                                        class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-black hover:bg-gray-200 transition-colors cursor-pointer"
                                        title="Haz clic para activar"
                                    >
                                        Inactivo
                                    </button>
                                @endif
                            </td>
                            <td class="px-3 py-3 text-center">
                                <div class="flex items-center justify-center gap-1">
                                    <button 
                                        wire:click="editUser({{ $user->id }})"
                                        class="text-blue-400 hover:text-blue-300 transition-colors"
                                        title="Editar"
                                    >
                                        <x-lucide name="edit" class="w-4 h-4" />
                                    </button>
                                    {{-- <button 
                                        wire:click="toggleStatus({{ $user->id }})"
                                        class="text-yellow-400 hover:text-yellow-300 transition-colors"
                                        title="{{ $user->status ? 'Desactivar' : 'Activar' }}"
                                    >
                                        <x-lucide name="toggle-left" class="w-4 h-4" />
                                    </button> --}}
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-3 py-12 text-center text-gray-400">
                                <x-lucide name="users" class="w-12 h-12 mx-auto mb-4 text-gray-600" />
                                <p class="text-lg">No se encontraron usuarios</p>
                                <p class="text-sm">Comienza agregando un nuevo usuario</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        <!-- Paginación -->
        @if($users->hasPages())
            <div class="px-3 py-4 bg-gray-700 border-t border-gray-600">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    <!-- Modal de Usuario -->
    @if($showModal)
        <div class="fixed inset-0 bg-black bg-opacity-50 flex items-start justify-center z-50 p-2 sm:p-4 overflow-y-auto">
            <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-[95%] sm:max-w-[75%] lg:max-w-[60%] my-4 sm:my-8">
                <div class="p-6 border-b border-gray-700">
                    <div class="flex items-center justify-between">
                        <h3 class="text-lg font-medium text-white">
                            {{ $editing ? 'Editar usuario' : 'Nuevo usuario' }}
                        </h3>
                        <button 
                            wire:click="closeModal"
                            class="text-gray-400 hover:text-white transition-colors"
                        >
                            <x-lucide name="x" class="w-6 h-6" />
                        </button>
                    </div>
                </div>

                <div class="max-h-[70vh] overflow-y-auto">
                <form wire:submit.prevent="saveUser" class="p-4 sm:p-6 space-y-4 sm:space-y-6">
                    <!-- Primera fila: Nombre, Email, RFC, CURP -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Nombre completo *
                            </label>
                            <input 
                                wire:model="name"
                                type="text" 
                                id="name"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base mobile-text"
                                placeholder="Ingrese el nombre completo"
                            >
                            @error('name') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Correo electrónico *
                            </label>
                            <input 
                                wire:model="email"
                                type="email" 
                                id="email"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base mobile-text"
                                placeholder="ejemplo@correo.com"
                            >
                            @error('email') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="rfc" class="block text-sm font-medium text-gray-300 mb-2">
                                RFC
                            </label>
                            <input 
                                wire:model="rfc"
                                type="text" 
                                id="rfc"
                                maxlength="13"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                placeholder="Ingrese el RFC (opcional)"
                            >
                            @error('rfc') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="curp" class="block text-sm font-medium text-gray-300 mb-2">
                                CURP *
                            </label>
                            <input
                                wire:model="curp"
                                type="text"
                                id="curp"
                                maxlength="18"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                placeholder="Ingrese la CURP"
                            >
                            @error('curp') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Segunda fila: Dirección, Posición, Sexo -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="direction" class="block text-sm font-medium text-gray-300 mb-2">
                                Dirección
                            </label>
                            <select 
                                wire:model="direction"
                                id="direction"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                x-on:change="
                                    if ($event.target.value === '__new__') {
                                        $event.target.value = '';
                                        $wire.set('direction', '');
                                        $refs.newDirectionInput.style.display = 'block';
                                        $refs.newDirectionInput.focus();
                                    } else {
                                        $refs.newDirectionInput.style.display = 'none';
                                    }
                                "
                            >
                                <option value="">Seleccionar dirección...</option>
                                @foreach($uniqueDirections as $dir)
                                    <option value="{{ $dir }}">{{ $dir }}</option>
                                @endforeach
                                <option value="__new__">+ Agregar nueva dirección</option>
                            </select>
                            
                            <!-- Campo de texto para nueva dirección -->
                            <div class="mt-2" x-ref="newDirectionInput" style="display: none;">
                                <input 
                                    type="text" 
                                    id="new_direction"
                                    placeholder="Escribir nueva dirección..."
                                    class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                    x-on:input="$wire.set('direction', $event.target.value)"
                                >
                            </div>
                            
                            @error('direction') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="position" class="block text-sm font-medium text-gray-300 mb-2">
                                Posición
                            </label>
                            <select 
                                wire:model="position"
                                id="position"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                x-on:change="
                                    if ($event.target.value === '__new__') {
                                        $event.target.value = '';
                                        $wire.set('position', '');
                                        $refs.newPositionInput.style.display = 'block';
                                        $refs.newPositionInput.focus();
                                    } else {
                                        $refs.newPositionInput.style.display = 'none';
                                    }
                                "
                            >
                                <option value="">Seleccionar posición...</option>
                                @foreach($uniquePositions as $pos)
                                    <option value="{{ $pos }}">{{ $pos }}</option>
                                @endforeach
                                <option value="__new__">+ Agregar nueva posición</option>
                            </select>
                            <!-- Campo de texto para nueva posición -->
                            <div class="mt-2" x-ref="newPositionInput" style="display: none;">
                                <input 
                                    type="text" 
                                    id="new_position"
                                    placeholder="Escribir nueva posición..."
                                    class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                    x-on:input="$wire.set('position', $event.target.value)"
                                >
                            </div>
                            @error('position') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="sex" class="block text-sm font-medium text-gray-300 mb-2">
                                Sexo
                            </label>
                            <select 
                                wire:model="sex"
                                id="sex"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                            >
                                <option value="">Seleccionar...</option>
                                <option value="masculino">Masculino</option>
                                <option value="femenino">Femenino</option>
                            </select>
                            @error('sex') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Tercera fila: Nivel, Tipo, Foto de Perfil -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div>
                            <label for="lvl" class="block text-sm font-medium text-gray-300 mb-2">
                                Nivel
                            </label>
                            <select 
                                wire:model="lvl"
                                id="lvl"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                            >
                                @for ($i = 1; $i <= 12; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                            @error('lvl') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label for="tipo" class="block text-sm font-medium text-gray-300 mb-2">
                                Tipo de usuario
                            </label>
                            <select 
                                wire:model="tipo"
                                id="tipo"
                                class="w-full px-3 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                            >
                                <option value="1">Administrador</option>
                                <option value="2">En Servicio Técnico</option>
                                <option value="3">Usuario</option>
                            </select>
                            @error('tipo') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>

                        <div x-data="{photoName: null, photoPreview: null}">
                            <label for="photo" class="block text-sm font-medium text-gray-300 mb-2">
                                Foto de perfil
                            </label>
                            
                            <!-- Input de archivo oculto -->
                            <input 
                                type="file" 
                                id="photo" 
                                class="hidden"
                                wire:model.live="photo"
                                x-ref="photo"
                                accept="image/*"
                                x-on:change="
                                    if ($refs.photo.files[0]) {
                                        photoName = $refs.photo.files[0].name;
                                        const reader = new FileReader();
                                        reader.onload = (e) => {
                                            photoPreview = e.target.result;
                                        };
                                        reader.readAsDataURL($refs.photo.files[0]);
                                    }
                                "
                            />

                            <!-- Foto actual (solo en edición) -->
                            @if($editing && $userId)
                                @php
                                    $currentUser = \App\Models\User::find($userId);
                                @endphp
                                <div class="mt-2" x-show="! photoPreview">
                                    @if($currentUser && $currentUser->hasProfilePhoto())
                                        <img src="{{ $currentUser->profile_photo_url }}" alt="{{ $currentUser->name }}" class="rounded-full size-16 object-cover">
                                    @else
                                        <div class="rounded-full size-16 bg-gray-600 flex items-center justify-center">
                                            <span class="text-lg font-medium text-white">{{ $currentUser->initials ?? '?' }}</span>
                                        </div>
                                    @endif
                                </div>
                            @endif

                            <!-- Vista previa de la nueva foto -->
                            <div class="mt-2" x-show="photoPreview" style="display: none;">
                                <span class="block rounded-full size-16 bg-cover bg-no-repeat bg-center"
                                      x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                                </span>
                            </div>

                            <!-- Botones -->
                            <div class="mt-2 flex flex-col sm:flex-row gap-2">
                                <button 
                                    type="button" 
                                    x-on:click.prevent="$refs.photo.click()"
                                    class="w-full sm:w-auto px-4 py-2 bg-gray-600 hover:bg-gray-500 text-white text-sm rounded-lg transition-colors"
                                >
                                    {{ $editing ? 'Cambiar' : 'Seleccionar' }}
                                </button>
                                
                                @if($editing && $userId && $currentUser && $currentUser->profile_photo_path)
                                    <button 
                                        type="button" 
                                        wire:click="deleteProfilePhoto"
                                        class="w-full sm:w-auto px-4 py-2 bg-red-600 hover:bg-red-500 text-white text-sm rounded-lg transition-colors"
                                    >
                                        Eliminar
                                    </button>
                                @endif
                            </div>

                            @error('photo') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                        </div>
                    </div>

                    <!-- Contraseña -->
                    @if(!$editing)
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                    Contraseña *
                                </label>
                                <div class="relative" x-data="{ show: false }">
                                <input 
                                    wire:model="password"
                                    x-bind:type="show ? 'text' : 'password'"
                                    type="password" 
                                    id="password"
                                    class="w-full px-3 py-3 pr-12 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                    placeholder="Mínimo 8 caracteres"
                                >
                                <x-password-toggle-button />
                                </div>
                                @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                    Confirmar Contraseña *
                                </label>
                                <div class="relative" x-data="{ show: false }">
                                <input 
                                    wire:model="password_confirmation"
                                    x-bind:type="show ? 'text' : 'password'"
                                    type="password" 
                                    id="password_confirmation"
                                    class="w-full px-3 py-3 pr-12 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                    placeholder="Confirme la contraseña"
                                >
                                <x-password-toggle-button />
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-300 mb-2">
                                    Nueva Contraseña (opcional)
                                </label>
                                <div class="relative" x-data="{ show: false }">
                                <input 
                                    wire:model="password"
                                    x-bind:type="show ? 'text' : 'password'"
                                    type="password" 
                                    id="password"
                                    class="w-full px-3 py-3 pr-12 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                    placeholder="Dejar vacío para mantener"
                                >
                                <x-password-toggle-button />
                                </div>
                                @error('password') <span class="text-red-400 text-sm">{{ $message }}</span> @enderror
                            </div>

                            <div>
                                <label for="password_confirmation" class="block text-sm font-medium text-gray-300 mb-2">
                                    Confirmar Nueva Contraseña
                                </label>
                                <div class="relative" x-data="{ show: false }">
                                <input 
                                    wire:model="password_confirmation"
                                    x-bind:type="show ? 'text' : 'password'"
                                    type="password" 
                                    id="password_confirmation"
                                    class="w-full px-3 py-3 pr-12 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-base"
                                    placeholder="Confirme la nueva contraseña"
                                >
                                <x-password-toggle-button />
                                </div>
                            </div>
                        </div>
                    @endif

                    <!-- Estado -->
                    <div class="flex items-center">
                        <input 
                            wire:model="status"
                            type="checkbox" 
                            id="status"
                            class="w-5 h-5 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500 focus:ring-2"
                        >
                        <label for="status" class="ml-3 text-base text-gray-300 cursor-pointer">
                            Usuario activo
                        </label>
                    </div>

                </form>
                </div>
                
                <!-- Botones fijos en la parte inferior -->
                <div class="p-4 sm:p-6 border-t border-gray-700 bg-gray-800">
                    <div class="flex flex-col sm:flex-row justify-end gap-3">
                        <button 
                            type="button"
                            wire:click="closeModal"
                            class="w-full sm:w-auto px-6 py-3 text-gray-300 bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors text-base"
                        >
                            Cancelar
                        </button>
                        <button 
                            type="button"
                            wire:click="saveUser"
                            class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors flex items-center justify-center gap-2 text-base"
                        >
                            <x-lucide name="save" class="w-4 h-4" />
                            {{ $editing ? 'Actualizar' : 'Crear' }}
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endif

<style>
    /* Mejoras para móviles */
    @media (max-width: 640px) {
        .mobile-touch-target {
            min-height: 44px;
            min-width: 44px;
        }
        
        .mobile-text {
            font-size: 16px; /* Previene zoom en iOS */
        }
        
        .mobile-card {
            touch-action: manipulation;
        }
    }
    
    /* Mejoras para la tabla en móviles */
    @media (max-width: 1024px) {
        .table-responsive {
            overflow-x: auto;
            -webkit-overflow-scrolling: touch;
        }
    }
    
    /* Permitir altura natural de las filas */
    .table-auto-height td {
        height: auto !important;
        min-height: auto !important;
        vertical-align: top;
    }
    
    .table-auto-height tr {
        height: auto !important;
        min-height: auto !important;
    }
    

</style>

<script>
    document.addEventListener('livewire:init', () => {
        Livewire.on('showMessage', message => {
            window.dispatchEvent(new CustomEvent('show-livewire-message', { detail: { message } }));
        });
    });

    window.addEventListener('show-livewire-message', event => {
        const msg = event.detail.message;
        let div = document.createElement('div');
        div.className = 'fixed top-6 left-1/2 transform -translate-x-1/2 bg-green-600 text-white px-6 py-3 rounded-lg shadow-lg z-50 max-w-[90vw] text-center';
        div.textContent = msg;
        document.body.appendChild(div);
        setTimeout(() => {
            div.style.transition = 'opacity 0.5s';
            div.style.opacity = '0';
            setTimeout(() => div.remove(), 500);
        }, 5000);
    });
    
    // Prevenir zoom en inputs en iOS
    document.addEventListener('DOMContentLoaded', function() {
        const inputs = document.querySelectorAll('input[type="text"], input[type="email"], input[type="password"]');
        inputs.forEach(input => {
            input.addEventListener('focus', function() {
                this.style.fontSize = '16px';
            });
        });
    });
</script>
</div>
