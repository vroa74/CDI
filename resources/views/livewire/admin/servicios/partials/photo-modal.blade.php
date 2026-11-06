<!-- Modal para agregar/editar fotos -->
@if ($showPhotoForm)
    <div class="app-modal service-modal fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-2 sm:p-4">
        <div class="bg-gray-800 rounded-lg shadow-xl w-full max-w-md">
            <div class="p-4 sm:p-6 border-b border-gray-700">
                <div class="flex items-center justify-between">
                    <h3 class="text-base sm:text-lg font-medium text-white">
                        @if ($editingPhotoIndex !== null)
                            Editar descripción de la foto
                        @else
                            Agregar Foto al Servicio
                        @endif
                    </h3>
                    <button @if ($editingPhotoIndex !== null) wire:click="cancelPhotoDescriptionEdit"
                        @else wire:click="closePhotoForm" @endif
                        class="modal-action text-white transition-colors bg-gray-600 hover:bg-gray-700">
                        <x-lucide name="x" class="w-5 h-5 sm:w-6 sm:h-6" />
                    </button>
                </div>
            </div>
            <div class="p-4 sm:p-6">
                @if ($editingPhotoIndex !== null)
                    <div class="mb-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Cambiar imagen (opcional)
                        </label>
                        <input type="file" wire:model="modalPhoto"
                            class="w-full text-gray-100 bg-gray-700 rounded p-2 sm:p-3 border border-gray-600 text-sm"
                            accept="image/*">
                        @error('modalPhoto')
                            <span class="text-red-400 text-xs sm:text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Descripción
                        </label>
                        <input type="text" wire:model="modalPhotoDescription"
                            class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="Descripción de la foto...">
                    </div>
                    <div class="mb-4">
                        <img src="{{ $modalPhotoPreview }}" alt="Preview" class="max-h-32 sm:max-h-40 mx-auto rounded">
                    </div>
                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="cancelPhotoDescriptionEdit"
                            class="modal-action text-white px-3 sm:px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors text-sm">
                            Cancelar
                        </button>
                        <button type="button" wire:click="savePhotoDescriptionEdit"
                            class="modal-action text-white px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700  rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                            <x-lucide name="save" class="w-3 h-3 sm:w-4 sm:h-4" />
                            Guardar
                        </button>
                    </div>
                @else
                    <div class="mb-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Seleccionar imagen
                        </label>
                        <input type="file" wire:model="modalPhoto"
                            class="w-full text-gray-100 bg-gray-700 rounded p-2 sm:p-3 border border-gray-600 text-sm"
                            accept="image/*">
                        @error('modalPhoto')
                            <span class="text-red-400 text-xs sm:text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="mb-4">
                        <label class="block text-xs sm:text-sm font-medium text-gray-300 mb-1 sm:mb-2">
                            Descripción (opcional)
                        </label>
                        <input type="text" wire:model="modalPhotoDescription"
                            class="w-full px-2 sm:px-3 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent text-sm"
                            placeholder="Descripción de la foto...">
                        @error('modalPhotoDescription')
                            <span class="text-red-400 text-xs sm:text-sm">{{ $message }}</span>
                        @enderror
                    </div>
                    @if ($modalPhotoPreview)
                        <div class="mb-4">
                            <img src="{{ $modalPhotoPreview }}" alt="Preview" class="max-h-32 sm:max-h-40 mx-auto rounded">
                        </div>
                    @endif
                    <div class="flex flex-col sm:flex-row justify-end gap-2 sm:gap-3 pt-4 border-t border-gray-700">
                        <button type="button" wire:click="closePhotoForm"
                            class="modal-action text-white px-3 sm:px-4 py-2  bg-gray-700 hover:bg-gray-600 rounded-lg transition-colors text-sm">
                            Cancelar
                        </button>
                        <button type="button" wire:click="addPhoto"
                            class="modal-action text-white px-3 sm:px-4 py-2 bg-green-600 hover:bg-green-700  rounded-lg transition-colors flex items-center justify-center gap-2 text-sm">
                            <x-lucide name="plus" class="w-3 h-3 sm:w-4 sm:h-4" />
                            Agregar Foto
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endif 