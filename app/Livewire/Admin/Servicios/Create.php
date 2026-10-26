<?php

namespace App\Livewire\Admin\Servicios;

use App\Models\Inventory;
use App\Models\Service;
use App\Models\ServicePhoto;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\WithFileUploads;

class Create extends Component
{
    use WithFileUploads;

    // Campos del formulario
    public $id_s = '';

    public $F_serv = '';

    public $solicitante_id = '';

    public $efectuo_id = '';

    public $vobo_id = '';

    public $obj_sol = '';

    public $actividades = '';

    public $mantenimiento = '';

    public $observaciones = '';

    public $solicitante_name = '';

    public $solicitante_position = '';

    public $solicitante_direction = '';

    public $efectuo_name = '';

    public $efectuo_position = '';

    public $efectuo_direction = '';

    public $vobo_name = '';

    public $vobo_position = '';

    public $vobo_direction = '';

    // Tipo de servicio
    public $correctivo = false;

    public $preventivo = false;

    public $transparencia = false;

    public $a_tec = false;

    public $web_ins = false;

    public $print = false;

    // Via de solicitud
    public $email = false;

    public $tel = false;

    public $sol_ser = false;

    public $oficio = false;

    public $calendario = false;

    public $capturo = '';

    public $status = false;

    public $impressions = false;

    // Método para manejar el cambio del checkbox calendario
    public function updatedCalendario($value)
    {
        if ($value) {
            // Si se marca el checkbox de calendario, llenar automáticamente los campos
            $this->obj_sol = 'MANTENIMIENTO DE EQUIPO DE CÓMPUTO.';
            $this->actividades = 'Mantenimiento calendarizado.';
            $this->observaciones = '<ul>
            </ul>
            <br/>
            <p class="text-5px font-bold">
            
            </p>';
            $this->mantenimiento = '<p></p>';

            // Disparar eventos para actualizar los textareas en la vista
            $this->dispatch('update-textarea', field: 'obj_sol', value: $this->obj_sol);
            $this->dispatch('update-textarea', field: 'actividades', value: $this->actividades);
            $this->dispatch('update-textarea', field: 'observaciones', value: $this->observaciones);
            $this->dispatch('update-textarea', field: 'mantenimiento', value: $this->mantenimiento);
        }
        // No limpiamos inventarios - permitimos que estén disponibles siempre
    }

    // Método para manejar el cambio del checkbox preventivo
    public function updatedPreventivo($value)
    {
        // No limpiamos inventarios - permitimos que estén disponibles siempre
    }

    // Modal properties
    public $showModal = false;

    public $modalTitle = '';

    public $modalType = '';

    public $userSearch = '';

    public $userSearchName = '';

    public $userSearchPosition = '';

    public $userSearchDirection = '';

    public $userSearchLvl = '';

    public $selectedUserId = null;

    public $selectedUserName = '';

    public $modalParam1 = null;

    public $modalParam2 = null;

    public $modalParam3 = null;

    public $modalParam4 = null;

    // Inventory Modal properties
    public $showInventoryModal = false;

    public $inventoryModalTitle = '';

    public $inventorySearchNi = '';

    public $inventorySearchSn = '';

    public $inventorySearchType = '';

    public $inventorySearchArticulo = '';

    public $inventorySearchUserName = '';

    public $inventorySearchUserDirection = '';

    public $selectedInventoryId = null;

    public $selectedInventory = null;

    // --- INICIO: Métodos Modal Inventario ---=================================================
    public $inventoryModalType = '';

    public $inventoryParam1 = null;

    public $inventoryParam2 = null;

    public $inventoryParam3 = null;

    public $inventoryParam4 = null;

    public $inventoryParam5 = null;

    // --- INICIO: Propiedades para Fotos ---=================================================
    public $servicePhotos = [];

    public $photoDescriptions = [];

    public $photoPreview = [];

    public $showPhotoForm = false;

    public $activePhotoFormId = null;

    public $modalPhoto = null;

    public $modalPhotoPreview = null;

    public $modalPhotoDescription = '';

    // --- FIN: Propiedades para Fotos ---=================================================

    public $editingPhotoIndex = null;

    // Track temporary photos for cleanup
    public $temporaryPhotos = [];

    // --- INICIO: Propiedades para Inventarios ---=================================================
    public $selectedInventories = [];

    public $showInventorySelection = false;

    // Computed property para obtener inventarios seleccionados con datos completos
    public function getSelectedInventoriesDataProperty()
    {
        if (empty($this->selectedInventories)) {
            return collect();
        }

        return Inventory::with(['assignedUser', 'responsible'])
            ->whereIn('id', $this->selectedInventories)
            ->get();
    }
    // --- FIN: Propiedades para Inventarios ---=================================================

    public function openInventoryModal($type = 'inventario', $param1 = null, $param2 = null, $param3 = null, $param4 = null, $param5 = null)
    {
        $this->inventoryModalType = $type;
        $this->inventoryParam1 = $param1;
        $this->inventoryParam2 = $param2;
        $this->inventoryParam3 = $param3;
        $this->inventoryParam4 = $param4;
        $this->inventoryParam5 = $param5;
        $this->inventoryModalTitle = "Seleccionar Inventario - Tipo: {$type}";
        // Mostrar parámetros en el título si no son nulos
        $params = array_filter([$param1, $param2, $param3, $param4, $param5], function ($param) {
            return $param !== null && $param !== '';
        });
        if (! empty($params)) {
            $this->inventoryModalTitle .= ' - Parámetros: '.implode(', ', $params);
        }
        $this->showInventoryModal = true;
        $this->inventorySearchNi = '';
        $this->inventorySearchSn = '';
        $this->inventorySearchType = '';
        $this->inventorySearchArticulo = '';
        $this->inventorySearchUserName = '';
        $this->inventorySearchUserDirection = '';
        $this->selectedInventoryId = null;
        $this->selectedInventory = null;
    }

    public function selectInventory($inventoryId)
    {
        try {
            Log::info('selectInventory iniciado', [
                'inventoryId' => $inventoryId,
                'modalType' => $this->inventoryModalType,
            ]);

            $inventory = Inventory::find($inventoryId);

            if (! $inventory) {
                Log::error('Inventario no encontrado', ['inventoryId' => $inventoryId]);
                session()->flash('error', 'El inventario seleccionado no fue encontrado.');

                return;
            }

            $this->selectedInventoryId = $inventoryId;
            $this->selectedInventory = $inventory;

            Log::info('Inventario encontrado', [
                'inventory' => $inventory->toArray(),
                'modalType' => $this->inventoryModalType,
            ]);

            // Si el primer parámetro es 'objetivo', concatenar al textarea obj_sol
            // ==============================================================================
            if ($this->inventoryModalType === 'objetivo') {
                $info = '<li>INVENTARIO: '
                .(! empty($inventory->type) ? "TYPE: {$inventory->type}, " : '')
                .(! empty($inventory->marca) ? "Marca: {$inventory->marca}, " : '')
                .(! empty($inventory->modelo) ? "Modelo: {$inventory->modelo}, " : '')
                .(! empty($inventory->articulo) ? "Artículo: {$inventory->articulo}, " : '')
                .(! empty($inventory->ni) ? "NI: {$inventory->ni}, " : '')
                .(! empty($inventory->ns) ? "SN: {$inventory->ns}" : '')
                .'. </li>';

                if (! empty($this->obj_sol)) {
                    $this->obj_sol .= "\n".$info;
                } else {
                    $this->obj_sol = $info;
                }
                $this->dispatch('update-textarea', field: 'obj_sol', value: $this->obj_sol);

                Log::info('Información agregada a objetivo', ['info' => $info]);
            }
            // ==============================================================================
            if ($this->inventoryModalType === 'actividades') {
                $info = '<li>INVENTARIO: '
                .(! empty($inventory->type) ? "TYPE: {$inventory->type}, " : '')
                .(! empty($inventory->marca) ? "Marca: {$inventory->marca}, " : '')
                .(! empty($inventory->modelo) ? "Modelo: {$inventory->modelo}, " : '')
                .(! empty($inventory->articulo) ? "Artículo: {$inventory->articulo}, " : '')
                .(! empty($inventory->ni) ? "NI: {$inventory->ni}, " : '')
                .(! empty($inventory->ns) ? "SN: {$inventory->ns}" : '')
                .'. </li>';
                if (! empty($this->actividades)) {
                    $this->actividades .= "\n".$info;
                } else {
                    $this->actividades = $info;
                }
                $this->dispatch('update-textarea', field: 'actividades', value: $this->actividades);

                Log::info('Información agregada a actividades', ['info' => $info]);
            }
            // ==============================================================================
            if ($this->inventoryModalType === 'observaciones') {
                $info = '<li>INVENTARIO: '
                .(! empty($inventory->type) ? "TYPE: {$inventory->type}, " : '')
                .(! empty($inventory->marca) ? "Marca: {$inventory->marca}, " : '')
                .(! empty($inventory->modelo) ? "Modelo: {$inventory->modelo}, " : '')
                .(! empty($inventory->articulo) ? "Artículo: {$inventory->articulo}, " : '')
                .(! empty($inventory->ni) ? "NI: {$inventory->ni}, " : '')
                .(! empty($inventory->ns) ? "SN: {$inventory->ns}" : '')
                .'. </li>';
                if (! empty($this->observaciones)) {
                    $this->observaciones .= "\n".$info;
                } else {
                    $this->observaciones = $info;
                }
                $this->dispatch('update-textarea', field: 'observaciones', value: $this->observaciones);

                Log::info('Información agregada a observaciones', ['info' => $info]);
            }
            // ==============================================================================
            if ($this->inventoryModalType === 'mantenimiento') {
                $info = '<li>INVENTARIO: '
                .(! empty($inventory->type) ? "TYPE: {$inventory->type}, " : '')
                .(! empty($inventory->marca) ? "Marca: {$inventory->marca}, " : '')
                .(! empty($inventory->modelo) ? "Modelo: {$inventory->modelo}, " : '')
                .(! empty($inventory->articulo) ? "Artículo: {$inventory->articulo}, " : '')
                .(! empty($inventory->ni) ? "NI: {$inventory->ni}, " : '')
                .(! empty($inventory->ns) ? "SN: {$inventory->ns}" : '')
                .'. </li>';
                if (! empty($this->mantenimiento)) {
                    $this->mantenimiento .= "\n".$info;
                } else {
                    $this->mantenimiento = $info;
                }
                $this->dispatch('update-textarea', field: 'mantenimiento', value: $this->mantenimiento);

                Log::info('Información agregada a mantenimiento', ['info' => $info]);
            }
            // ==============================================================================

            Log::info('selectInventory completado exitosamente');
            $this->closeInventoryModal();

        } catch (\Exception $e) {
            Log::error('Error en selectInventory', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'inventoryId' => $inventoryId,
            ]);

            session()->flash('error', 'Error al seleccionar el inventario: '.$e->getMessage());
            $this->closeInventoryModal();
        }
    }

    public function closeInventoryModal()
    {
        $this->showInventoryModal = false;
        $this->inventoryModalTitle = '';
        $this->inventoryModalType = '';
        $this->inventoryParam1 = null;
        $this->inventoryParam2 = null;
        $this->inventoryParam3 = null;
        $this->inventoryParam4 = null;
        $this->inventoryParam5 = null;
        $this->inventorySearchNi = '';
        $this->inventorySearchSn = '';
        $this->inventorySearchType = '';
        $this->inventorySearchArticulo = '';
        $this->inventorySearchUserName = '';
        $this->inventorySearchUserDirection = '';
        $this->selectedInventoryId = null;
        $this->selectedInventory = null;
    }
    // --- FIN: Métodos Modal Inventario ---=================================================

    // --- INICIO: Métodos para Fotos ---=================================================
    public function openPhotoForm($index = null)
    {
        if ($index !== null) {
            // Editar descripción de foto existente
            $this->editingPhotoIndex = $index;
            $this->modalPhotoDescription = $this->servicePhotos[$index]['description'] ?? '';
            $this->modalPhotoPreview = $this->servicePhotos[$index]['preview'] ?? null;
            $this->showPhotoForm = true;
        } else {
            // Agregar nueva foto
            $this->editingPhotoIndex = null;
            $this->showPhotoForm = true;
            $this->modalPhoto = null;
            $this->modalPhotoPreview = null;
            $this->modalPhotoDescription = '';
        }
    }

    public function closePhotoForm()
    {
        $this->showPhotoForm = false;
        $this->modalPhoto = null;
        $this->modalPhotoPreview = null;
        $this->modalPhotoDescription = '';
    }

    public function updatedModalPhoto()
    {
        if ($this->modalPhoto) {
            $this->modalPhotoPreview = $this->modalPhoto->temporaryUrl();
        }
    }

    public function addPhoto()
    {
        try {
            // Validación más específica
            $this->validate([
                'modalPhoto' => [
                    'required',
                    'file',
                    'image',
                    'mimes:jpeg,png,jpg,gif,webp',
                    'max:2048', // 2MB máximo
                ],
                'modalPhotoDescription' => 'nullable|string|max:255',
            ], [
                'modalPhoto.required' => 'Debe seleccionar una imagen.',
                'modalPhoto.file' => 'El archivo no es válido.',
                'modalPhoto.image' => 'El archivo debe ser una imagen.',
                'modalPhoto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
                'modalPhoto.max' => 'La imagen no puede ser mayor a 2MB.',
                'modalPhotoDescription.max' => 'La descripción no puede tener más de 255 caracteres.',
            ]);

            Log::info('Iniciando addPhoto', [
                'modalPhoto' => $this->modalPhoto ? 'present' : 'null',
                'modalPhotoDescription' => $this->modalPhotoDescription,
                'modalPhotoPreview' => $this->modalPhotoPreview,
                'fileSize' => $this->modalPhoto ? $this->modalPhoto->getSize() : 'N/A',
                'fileName' => $this->modalPhoto ? $this->modalPhoto->getClientOriginalName() : 'N/A',
                'mimeType' => $this->modalPhoto ? $this->modalPhoto->getMimeType() : 'N/A',
            ]);

            // Verificar que el archivo no esté corrupto
            if (! $this->modalPhoto->isValid()) {
                throw new \Exception('El archivo de imagen está corrupto o no es válido.');
            }

            // Verificar el tamaño del archivo
            if ($this->modalPhoto->getSize() > 2 * 1024 * 1024) { // 2MB en bytes
                throw new \Exception('El archivo es demasiado grande. Máximo 2MB permitido.');
            }

            // Verificar el tipo MIME
            $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
            if (! in_array($this->modalPhoto->getMimeType(), $allowedMimes)) {
                throw new \Exception('Tipo de archivo no permitido. Solo se permiten imágenes.');
            }

            $path = $this->modalPhoto->store('service_photos', 'public');

            // Track temporary photo for cleanup if service update fails
            $this->temporaryPhotos[] = $path;

            $photoData = [
                'path' => $path,
                'description' => $this->modalPhotoDescription,
                'preview' => $this->modalPhotoPreview,
            ];

            $this->servicePhotos[] = $photoData;

            // Log para debugging
            Log::info('Foto agregada al array exitosamente', [
                'path' => $path,
                'description' => $this->modalPhotoDescription,
                'totalPhotos' => count($this->servicePhotos),
                'allPhotos' => $this->servicePhotos,
                'photoData' => $photoData,
            ]);

            $this->closePhotoForm();
            session()->flash('message', 'Foto agregada correctamente.');

        } catch (ValidationException $e) {
            Log::error('Error de validación en addPhoto', [
                'errors' => $e->errors(),
                'modalPhoto' => $this->modalPhoto ? 'present' : 'null',
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error en addPhoto', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'modalPhoto' => $this->modalPhoto ? 'present' : 'null',
            ]);

            // Limpiar archivo temporal si se subió
            if ($this->modalPhoto && $this->modalPhoto->isValid()) {
                try {
                    $tempPath = $this->modalPhoto->getRealPath();
                    if ($tempPath && file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                } catch (\Exception $cleanupError) {
                    Log::warning('No se pudo limpiar archivo temporal', ['error' => $cleanupError->getMessage()]);
                }
            }

            session()->flash('error', 'Error al agregar la foto: '.$e->getMessage());
            throw $e;
        }
    }

    public function deletePhoto($index)
    {
        if (isset($this->servicePhotos[$index])) {
            $photo = $this->servicePhotos[$index];

            // Eliminar archivo físico si es una foto temporal
            if (isset($photo['path']) && ! isset($photo['id'])) {
                if (Storage::disk('public')->exists($photo['path'])) {
                    Storage::disk('public')->delete($photo['path']);
                }
                // Remove from temporary photos if it's there
                $key = array_search($photo['path'], $this->temporaryPhotos);
                if ($key !== false) {
                    unset($this->temporaryPhotos[$key]);
                }
            }

            unset($this->servicePhotos[$index]);
            $this->servicePhotos = array_values($this->servicePhotos); // Reindexar array

            Log::info('Foto eliminada del array', ['index' => $index, 'photo' => $photo]);
        }
    }

    public function deletePhotoFromDatabase($photoId)
    {
        $photo = ServicePhoto::find($photoId);
        if ($photo) {
            Storage::disk('public')->delete($photo->photo_path);
            $photo->delete();
            session()->flash('message', 'Foto eliminada correctamente.');
        }
    }

    public function savePhotoDescriptionEdit()
    {
        try {
            if ($this->editingPhotoIndex !== null) {
                Log::info('Iniciando savePhotoDescriptionEdit', [
                    'editingPhotoIndex' => $this->editingPhotoIndex,
                    'hasNewPhoto' => $this->modalPhoto ? 'yes' : 'no',
                    'description' => $this->modalPhotoDescription,
                ]);

                // If a new photo was uploaded, replace the old one
                if ($this->modalPhoto) {
                    // Validación más específica para la nueva foto
                    $this->validate([
                        'modalPhoto' => [
                            'required',
                            'file',
                            'image',
                            'mimes:jpeg,png,jpg,gif,webp',
                            'max:2048', // 2MB máximo
                        ],
                    ], [
                        'modalPhoto.required' => 'Debe seleccionar una imagen.',
                        'modalPhoto.file' => 'El archivo no es válido.',
                        'modalPhoto.image' => 'El archivo debe ser una imagen.',
                        'modalPhoto.mimes' => 'La imagen debe ser de tipo: jpeg, png, jpg, gif, webp.',
                        'modalPhoto.max' => 'La imagen no puede ser mayor a 2MB.',
                    ]);

                    // Verificar que el archivo no esté corrupto
                    if (! $this->modalPhoto->isValid()) {
                        throw new \Exception('El archivo de imagen está corrupto o no es válido.');
                    }

                    // Verificar el tamaño del archivo
                    if ($this->modalPhoto->getSize() > 2 * 1024 * 1024) { // 2MB en bytes
                        throw new \Exception('El archivo es demasiado grande. Máximo 2MB permitido.');
                    }

                    // Verificar el tipo MIME
                    $allowedMimes = ['image/jpeg', 'image/png', 'image/jpg', 'image/gif', 'image/webp'];
                    if (! in_array($this->modalPhoto->getMimeType(), $allowedMimes)) {
                        throw new \Exception('Tipo de archivo no permitido. Solo se permiten imágenes.');
                    }

                    // Delete old photo file if it's a temporary one
                    $oldPhoto = $this->servicePhotos[$this->editingPhotoIndex];
                    if (isset($oldPhoto['path']) && ! isset($oldPhoto['id'])) {
                        // Solo eliminar si es una foto temporal (nueva)
                        if (Storage::disk('public')->exists($oldPhoto['path'])) {
                            Storage::disk('public')->delete($oldPhoto['path']);
                            Log::info('Foto temporal anterior eliminada', ['path' => $oldPhoto['path']]);
                        }
                        // Remove from temporary photos if it's there
                        $key = array_search($oldPhoto['path'], $this->temporaryPhotos);
                        if ($key !== false) {
                            unset($this->temporaryPhotos[$key]);
                        }
                    }

                    // Store new photo
                    $newPath = $this->modalPhoto->store('service_photos', 'public');
                    $this->temporaryPhotos[] = $newPath;

                    Log::info('Nueva foto almacenada', [
                        'oldPath' => $oldPhoto['path'] ?? 'N/A',
                        'newPath' => $newPath,
                        'editingPhotoIndex' => $this->editingPhotoIndex,
                    ]);

                    // Update photo data
                    $this->servicePhotos[$this->editingPhotoIndex]['path'] = $newPath;
                    $this->servicePhotos[$this->editingPhotoIndex]['preview'] = $this->modalPhotoPreview;

                    // If this was an existing photo, mark it as modified
                    if (isset($oldPhoto['id'])) {
                        $this->servicePhotos[$this->editingPhotoIndex]['modified'] = true;
                        Log::info('Foto existente marcada como modificada', ['id' => $oldPhoto['id'], 'newPath' => $newPath]);
                    }
                }

                // Update description
                $this->servicePhotos[$this->editingPhotoIndex]['description'] = $this->modalPhotoDescription;

                Log::info('Descripción de foto actualizada', [
                    'index' => $this->editingPhotoIndex,
                    'description' => $this->modalPhotoDescription,
                ]);

                $this->editingPhotoIndex = null;
                $this->showPhotoForm = false;
                $this->modalPhotoDescription = '';
                $this->modalPhotoPreview = null;
                $this->modalPhoto = null;

                session()->flash('message', 'Foto actualizada correctamente.');
            } else {
                Log::warning('savePhotoDescriptionEdit llamado sin editingPhotoIndex válido');
                session()->flash('error', 'No se pudo identificar la foto a editar.');
            }
        } catch (ValidationException $e) {
            Log::error('Error de validación en savePhotoDescriptionEdit', [
                'errors' => $e->errors(),
                'editingPhotoIndex' => $this->editingPhotoIndex,
            ]);
            throw $e;
        } catch (\Exception $e) {
            Log::error('Error en savePhotoDescriptionEdit', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
                'editingPhotoIndex' => $this->editingPhotoIndex,
            ]);

            // Limpiar archivo temporal si se subió
            if ($this->modalPhoto && $this->modalPhoto->isValid()) {
                try {
                    $tempPath = $this->modalPhoto->getRealPath();
                    if ($tempPath && file_exists($tempPath)) {
                        unlink($tempPath);
                    }
                } catch (\Exception $cleanupError) {
                    Log::warning('No se pudo limpiar archivo temporal', ['error' => $cleanupError->getMessage()]);
                }
            }

            session()->flash('error', 'Error al actualizar la foto: '.$e->getMessage());
            throw $e;
        }
    }

    public function cancelPhotoDescriptionEdit()
    {
        $this->editingPhotoIndex = null;
        $this->showPhotoForm = false;
        $this->modalPhotoDescription = '';
        $this->modalPhotoPreview = null;
    }
    // --- FIN: Métodos para Fotos ---=================================================

    // --- INICIO: Métodos para Inventarios ---=================================================
    public function openInventorySelection()
    {
        $this->showInventorySelection = true;
    }

    public function closeInventorySelection()
    {
        $this->showInventorySelection = false;
    }

    public function addInventoryToService($inventoryId)
    {
        try {
            $inventory = Inventory::find($inventoryId);

            if (! $inventory) {
                session()->flash('error', 'El inventario seleccionado no fue encontrado.');

                return;
            }

            // Verificar si ya está seleccionado
            if (in_array($inventoryId, $this->selectedInventories)) {
                session()->flash('warning', 'Este inventario ya está seleccionado.');

                return;
            }

            // Agregar a la lista de seleccionados
            $this->selectedInventories[] = $inventoryId;

            Log::info('Inventario agregado al servicio', [
                'inventoryId' => $inventoryId,
                'inventory' => $inventory->toArray(),
                'selectedInventories' => $this->selectedInventories,
            ]);

            session()->flash('message', 'Inventario agregado correctamente.');

        } catch (\Exception $e) {
            Log::error('Error al agregar inventario', [
                'error' => $e->getMessage(),
                'inventoryId' => $inventoryId,
            ]);

            session()->flash('error', 'Error al agregar el inventario: '.$e->getMessage());
        }
    }

    public function removeInventoryFromService($inventoryId)
    {
        $key = array_search($inventoryId, $this->selectedInventories);
        if ($key !== false) {
            unset($this->selectedInventories[$key]);
            $this->selectedInventories = array_values($this->selectedInventories); // Reindexar

            // No hay datos adicionales que limpiar

            Log::info('Inventario removido del servicio', [
                'inventoryId' => $inventoryId,
                'selectedInventories' => $this->selectedInventories,
            ]);

            session()->flash('message', 'Inventario removido correctamente.');
        }
    }
    // --- FIN: Métodos para Inventarios ---=================================================

    protected $rules = [
        'id_s' => 'nullable|string|max:25',
        'F_serv' => 'nullable|date',
        'solicitante_id' => 'required|exists:users,id',
        'efectuo_id' => 'required|exists:users,id',
        'vobo_id' => 'required|exists:users,id',
        'obj_sol' => 'required|string',
        'actividades' => 'required|string',
        'mantenimiento' => 'nullable|string',
        'observaciones' => 'nullable|string',
        'correctivo' => 'boolean',
        'preventivo' => 'boolean',
        'transparencia' => 'boolean',
        'a_tec' => 'boolean',
        'web_ins' => 'boolean',
        'print' => 'boolean',
        'email' => 'boolean',
        'tel' => 'boolean',
        'sol_ser' => 'boolean',
        'oficio' => 'boolean',
        'calendario' => 'boolean',
        'capturo' => 'required|exists:users,id',
        'status' => 'boolean',
        'impressions' => 'boolean',
    ];

    protected $messages = [
        'F_serv.date' => 'La fecha debe ser válida',
        'solicitante_id.exists' => 'El solicitante debe ser un usuario válido',
        'efectuo_id.exists' => 'El usuario que efectuó debe ser válido',
        'vobo_id.exists' => 'El usuario de VºBº debe ser válido',
        'capturo.exists' => 'El usuario que captura debe ser válido',
    ];

    public function mount()
    {
        // Inicializar con valores por defecto para creación
        $now = now();
        $this->id_s = $now->format('Y-m-d/H:i:s');

        $this->F_serv = now()->format('Y-m-d');
        $this->capturo = Auth::id();
        $this->status = false;
        $this->impressions = false;

        // Inicializar arrays vacíos
        $this->servicePhotos = [];
        $this->selectedInventories = [];
    }

    // --- INICIO: Métodos Modal Usuario ---
    public function openUserModal($type, $param1 = null, $param2 = null, $param3 = null, $param4 = null)
    {
        $this->modalType = $type;
        $this->modalParam1 = ($param1 === 'null') ? '' : $param1;
        $this->modalParam2 = ($param2 === 'null') ? '' : $param2;
        $this->modalParam3 = ($param3 === 'null') ? '' : $param3;
        $this->modalParam4 = ($param4 === 'null') ? '' : $param4;

        // Aplica los filtros iniciales
        if (! empty($this->modalParam1)) {
            $this->userSearchName = $this->modalParam1;
        }
        if (! empty($this->modalParam2)) {
            $this->userSearchDirection = $this->modalParam2;
        }
        if (! empty($this->modalParam3)) {
            $this->userSearchLvl = $this->modalParam3;
        }
        if (! empty($this->modalParam4)) {
            $this->userSearchPosition = $this->modalParam4;
        }

        $this->showModal = true;
        $this->selectedUserId = null;
        $this->selectedUserName = '';
    }

    public function selectUser($userId, $userName)
    {
        Log::info('selectUser ejecutado', ['id' => $userId, 'name' => $userName, 'modalType' => $this->modalType]);
        $miperfil = User::find($userId);
        $this->selectedUserId = $userId;
        $this->selectedUserName = $userName;

        // Dependiendo del tipo, envía la info al campo correspondiente
        if ($this->modalType === 'Solicitante') {
            $this->solicitante_id = $userId;
            $this->solicitante_name = $userName;
            $this->solicitante_position = $miperfil->position;
            $this->solicitante_direction = $miperfil->direction;
        } elseif ($this->modalType === 'efectuo') {
            $this->efectuo_id = $userId;
            $this->efectuo_name = $userName;
            $this->efectuo_position = $miperfil->position;
            $this->efectuo_direction = $miperfil->direction;
        } elseif ($this->modalType === 'vobo') {
            $this->vobo_id = $userId;
            $this->vobo_name = $userName;
            $this->vobo_position = $miperfil->position;
            $this->vobo_direction = $miperfil->direction;
        } elseif ($this->modalType === 'objetivo') {
            // Concatenar a obj_sol
            $userInfo =
                        (! empty(trim($miperfil->name)) ? "<b>NOMBRE:</b> {$miperfil->name}    " : '').
                        (! empty(trim($miperfil->direction)) ? "<b>DIRECCION:</b> {$miperfil->direction}    " : '').
                        (! empty(trim($miperfil->position)) ? "<b>CARGO:</b> {$miperfil->position}" : '').'.';
            if (! empty($this->obj_sol)) {
                $this->obj_sol .= "\n".$userInfo;
            } else {
                $this->obj_sol = $userInfo;
            }
            $this->dispatch('update-textarea', field: 'obj_sol', value: $this->obj_sol);
        } elseif ($this->modalType === 'actividades') {
            $userInfo =
                        (! empty(trim($miperfil->name)) ? "<b>NOMBRE:</b> {$miperfil->name}    " : '').
                        (! empty(trim($miperfil->direction)) ? "<b>DIRECCION:</b> {$miperfil->direction}    " : '').
                        (! empty(trim($miperfil->position)) ? "<b>CARGO:</b> {$miperfil->position}" : '').'.';
            if (! empty($this->actividades)) {
                $this->actividades .= "\n".$userInfo;
            } else {
                $this->actividades = $userInfo;
            }
            $this->dispatch('update-textarea', field: 'actividades', value: $this->actividades);
        } elseif ($this->modalType === 'observaciones') {
            $userInfo =
                        (! empty(trim($miperfil->name)) ? "<b>NOMBRE:</b> {$miperfil->name}    " : '').
                        (! empty(trim($miperfil->direction)) ? "<b>DIRECCION:</b> {$miperfil->direction}    " : '').
                        (! empty(trim($miperfil->position)) ? "<b>CARGO:</b> {$miperfil->position}" : '').'.';
            if (! empty($this->observaciones)) {
                $this->observaciones .= "\n".$userInfo;
            } else {
                $this->observaciones = $userInfo;
            }
            $this->dispatch('update-textarea', field: 'observaciones', value: $this->observaciones);
        } elseif ($this->modalType === 'mantenimiento') {
            $userInfo =
                        (! empty(trim($miperfil->name)) ? "<b>NOMBRE:</b> {$miperfil->name}    " : '').
                        (! empty(trim($miperfil->direction)) ? "<b>DIRECCION:</b> {$miperfil->direction}    " : '').
                        (! empty(trim($miperfil->position)) ? "<b>CARGO:</b> {$miperfil->position}" : '').'.';
            if (! empty($this->mantenimiento)) {
                $this->mantenimiento .= "\n".$userInfo;
            } else {
                $this->mantenimiento = $userInfo;
            }
            $this->dispatch('update-textarea', field: 'mantenimiento', value: $this->mantenimiento);
        }

        $this->closeModal();   // mantenimiento
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->modalType = '';
        $this->modalTitle = '';
        $this->userSearch = '';
        $this->userSearchName = '';
        $this->userSearchPosition = '';
        $this->userSearchDirection = '';
        $this->userSearchLvl = '';
        $this->selectedUserId = null;
        $this->selectedUserName = '';
    }

    public function clearUserFilters()
    {
        $this->userSearchName = '';
        $this->userSearchPosition = '';
        $this->userSearchDirection = '';
        $this->userSearchLvl = '';
    }
    // --- FIN: Métodos Modal Usuario ---====================================================

    public function saveService()
    {
        if (blank($this->id_s)) {
            $this->id_s = now()->format('Y-m-d/H:i:s');
        }

        $this->validate();

        // Validación personalizada: al menos una vía de solicitud
        if (! ($this->email || $this->tel || $this->sol_ser || $this->oficio || $this->calendario)) {
            $this->addError('via_solicitud', 'Debes seleccionar al menos una opción en Vía de Solicitud.');

            return;
        }
        // Validación personalizada: al menos un tipo de servicio
        if (! ($this->correctivo || $this->preventivo || $this->transparencia || $this->a_tec || $this->web_ins || $this->print)) {
            $this->addError('tipo_servicio', 'Debes seleccionar al menos una opción en Tipo de Servicio.');

            return;
        }

        try {
            // Log para debugging
            Log::info('Iniciando creación de servicio', [
                'photosCount' => count($this->servicePhotos),
                'photos' => $this->servicePhotos,
            ]);

            // Crear el nuevo servicio
            $service = Service::create([
                'id_s' => $this->id_s,
                'F_serv' => $this->F_serv,
                'solicitante_id' => $this->solicitante_id,
                'efectuo_id' => $this->efectuo_id,
                'vobo_id' => $this->vobo_id,
                'obj_sol' => $this->obj_sol,
                'actividades' => $this->actividades,
                'mantenimiento' => $this->mantenimiento,
                'observaciones' => $this->observaciones,
                'correctivo' => $this->correctivo,
                'preventivo' => $this->preventivo,
                'transparencia' => $this->transparencia,
                'a_tec' => $this->a_tec,
                'web_ins' => $this->web_ins,
                'print' => $this->print,
                'email' => $this->email,
                'tel' => $this->tel,
                'sol_ser' => $this->sol_ser,
                'oficio' => $this->oficio,
                'calendario' => $this->calendario,
                'capturo' => $this->capturo,
                'status' => $this->status,
                'impressions' => $this->impressions,
            ]);

            // Procesar fotos del servicio
            Log::info('Iniciando procesamiento de fotos', [
                'servicePhotos' => $this->servicePhotos,
                'count' => count($this->servicePhotos),
            ]);

            // Crear todas las fotos
            $newPhotosCreated = 0;

            foreach ($this->servicePhotos as $index => $photoData) {
                Log::info("Procesando foto {$index}", $photoData);

                try {
                    $newPhoto = ServicePhoto::create([
                        'service_id' => $service->id,
                        'photo_path' => $photoData['path'],
                        'description' => $photoData['description'] ?? '',
                    ]);
                    $newPhotosCreated++;
                    Log::info('Nueva foto creada exitosamente', ['id' => $newPhoto->id, 'path' => $photoData['path']]);
                } catch (\Exception $e) {
                    Log::error('Error creando nueva foto', [
                        'error' => $e->getMessage(),
                        'photoData' => $photoData,
                    ]);
                }
            }

            Log::info('Resumen de procesamiento de fotos', [
                'newPhotosCreated' => $newPhotosCreated,
                'totalProcessed' => count($this->servicePhotos),
            ]);

            // Procesar inventarios del servicio (siempre, sin importar checkboxes)
            if (! empty($this->selectedInventories)) {
                Log::info('Iniciando procesamiento de inventarios', [
                    'selectedInventories' => $this->selectedInventories,
                    'solicitante_id' => $this->solicitante_id,
                    'F_serv' => $this->F_serv,
                ]);

                // Sincronizar inventarios con el servicio
                $inventoryData = [];
                foreach ($this->selectedInventories as $inventoryId) {
                    $inventoryData[$inventoryId] = [
                        'user_id' => $this->solicitante_id,
                        'service_date' => $this->F_serv,
                    ];
                }

                $service->inventories()->sync($inventoryData);

                Log::info('Inventarios sincronizados exitosamente', [
                    'inventoryData' => $inventoryData,
                    'count' => count($inventoryData),
                ]);
            }

            // Clear temporary photos tracking since service was created successfully
            $this->temporaryPhotos = [];

            session()->flash('message', 'Servicio creado correctamente.');

            // Redirigir a la lista de servicios
            return redirect()->route('servicios.index');
        } catch (\Exception $e) {
            // If service creation fails, cleanup temporary photos
            Log::error('Error al crear servicio', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            $this->cleanupTemporaryPhotos();
            throw $e;
        }
    }

    public function cleanupTemporaryPhotos()
    {
        // SOLO limpiar fotos temporales que no se guardaron exitosamente
        // NO eliminar fotos que ya están en el array servicePhotos
        foreach ($this->temporaryPhotos as $photoPath) {
            // Verificar si esta foto ya está en servicePhotos
            $isInServicePhotos = false;
            foreach ($this->servicePhotos as $photoData) {
                if (isset($photoData['path']) && $photoData['path'] === $photoPath) {
                    $isInServicePhotos = true;
                    break;
                }
            }

            // Solo eliminar si no está en servicePhotos
            if (! $isInServicePhotos && Storage::disk('public')->exists($photoPath)) {
                Storage::disk('public')->delete($photoPath);
                Log::info('Eliminando foto temporal no guardada', ['path' => $photoPath]);
            }
        }
        $this->temporaryPhotos = [];
    }

    // COMENTADO: Este método estaba eliminando fotos cuando el componente se destruía
    // public function dehydrate()
    // {
    //     // Cleanup temporary photos when component is destroyed
    //     $this->cleanupTemporaryPhotos();
    // }

    // COMENTADO: Este método estaba eliminando fotos cuando el array se vaciaba
    // public function updated($propertyName)
    // {
    //     // If user navigates away without saving, cleanup temporary photos
    //     if ($propertyName === 'servicePhotos' && empty($this->servicePhotos)) {
    //         $this->cleanupTemporaryPhotos();
    //     }
    // }

    // Método para limpiar archivos temporales cuando el componente se destruye
    public function dehydrate()
    {
        // Solo limpiar fotos temporales que no se guardaron exitosamente
        if (! empty($this->temporaryPhotos)) {
            Log::info('Limpiando archivos temporales en dehydrate', [
                'temporaryPhotos' => $this->temporaryPhotos,
            ]);
            $this->cleanupTemporaryPhotos();
        }
    }

    // Método para manejar errores de validación específicos
    public function updated($propertyName)
    {
        // Log para debugging de cambios en propiedades
        if (in_array($propertyName, ['modalPhoto', 'inventorySearchNi', 'inventorySearchSn'])) {
            Log::info("Propiedad actualizada: {$propertyName}", [
                'value' => $this->{$propertyName},
            ]);
        }

        // Si se actualiza modalPhoto, generar preview
        if ($propertyName === 'modalPhoto' && $this->modalPhoto) {
            try {
                $this->modalPhotoPreview = $this->modalPhoto->temporaryUrl();
                Log::info('Preview generado para modalPhoto', [
                    'fileName' => $this->modalPhoto->getClientOriginalName(),
                    'fileSize' => $this->modalPhoto->getSize(),
                    'mimeType' => $this->modalPhoto->getMimeType(),
                ]);
            } catch (\Exception $e) {
                Log::error('Error generando preview de modalPhoto', [
                    'error' => $e->getMessage(),
                ]);
            }
        }
    }

    // Método para verificar configuración del servidor
    public function checkServerConfiguration()
    {
        $warnings = [];

        // Verificar límites de PHP
        $uploadMaxFilesize = ini_get('upload_max_filesize');
        $postMaxSize = ini_get('post_max_size');
        $maxExecutionTime = ini_get('max_execution_time');
        $memoryLimit = ini_get('memory_limit');

        // Convertir a bytes para comparación
        $uploadMaxBytes = $this->convertToBytes($uploadMaxFilesize);
        $postMaxBytes = $this->convertToBytes($postMaxSize);
        $memoryLimitBytes = $this->convertToBytes($memoryLimit);

        if ($uploadMaxBytes < 2 * 1024 * 1024) { // Menos de 2MB
            $warnings[] = "upload_max_filesize está configurado en {$uploadMaxFilesize}. Se recomienda al menos 2M para subir imágenes.";
        }

        if ($postMaxBytes < 10 * 1024 * 1024) { // Menos de 10MB
            $warnings[] = "post_max_size está configurado en {$postMaxSize}. Se recomienda al menos 10M para subir múltiples archivos.";
        }

        if ($maxExecutionTime < 300) { // Menos de 5 minutos
            $warnings[] = "max_execution_time está configurado en {$maxExecutionTime} segundos. Se recomienda al menos 300 para procesar archivos grandes.";
        }

        if ($memoryLimitBytes < 128 * 1024 * 1024) { // Menos de 128MB
            $warnings[] = "memory_limit está configurado en {$memoryLimit}. Se recomienda al menos 128M para procesar imágenes.";
        }

        return $warnings;
    }

    // Método auxiliar para convertir tamaños a bytes
    private function convertToBytes($sizeStr)
    {
        $sizeStr = strtolower(trim($sizeStr));
        $last = strtolower($sizeStr[strlen($sizeStr) - 1]);
        $size = (int) $sizeStr;

        switch ($last) {
            case 'g':
                $size *= 1024;
            case 'm':
                $size *= 1024;
            case 'k':
                $size *= 1024;
        }

        return $size;
    }

    public function render()
    {
        $users = User::orderBy('name')->get();

        // Filtrar usuarios para el modal
        $filteredUsers = User::query()
            // Si es modal de efectuó, filtrar solo por informática
            ->when($this->modalType === 'efectuo', function ($query) {
                $query->where('direction', 'like', '%INFORMATICA%');
            })
            // Si es modal de VºBº, filtrar solo directores con nivel 4
            ->when($this->modalType === 'vobo', function ($query) {
                $query->where('position', 'like', '%DIRECTOR%')
                    ->where('lvl', 4);
            })
            ->when($this->userSearchName, function ($query) {
                $query->where('name', 'like', '%'.$this->userSearchName.'%');
            })
            ->when($this->userSearchPosition, function ($query) {
                $query->where('position', 'like', '%'.$this->userSearchPosition.'%');
            })
            ->when($this->userSearchDirection, function ($query) {
                $query->where('direction', 'like', '%'.$this->userSearchDirection.'%');
            })
            ->when($this->userSearchLvl, function ($query) {
                $query->where('lvl', 'like', '%'.$this->userSearchLvl.'%');
            })
            ->orderBy('name')
            ->get();

        // Filtrar inventarios para el modal
        $filteredInventories = Inventory::with(['assignedUser', 'responsible'])
            ->where('status', true)
            ->when($this->inventorySearchNi, function ($query) {
                $query->where('ni', 'like', '%'.$this->inventorySearchNi.'%');
            })
            ->when($this->inventorySearchSn, function ($query) {
                $query->where('ns', 'like', '%'.$this->inventorySearchSn.'%');
            })
            ->when($this->inventorySearchType, function ($query) {
                $query->where('type', 'like', '%'.$this->inventorySearchType.'%');
            })
            ->when($this->inventorySearchArticulo, function ($query) {
                $query->where('articulo', 'like', '%'.$this->inventorySearchArticulo.'%');
            })
            ->when($this->inventorySearchUserName, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('assignedUser', function ($userQuery) {
                        $userQuery->where('name', 'like', '%'.$this->inventorySearchUserName.'%');
                    })
                        ->orWhereHas('responsible', function ($userQuery) {
                            $userQuery->where('name', 'like', '%'.$this->inventorySearchUserName.'%');
                        });
                });
            })
            ->when($this->inventorySearchUserDirection, function ($query) {
                $query->where(function ($subQuery) {
                    $subQuery->whereHas('assignedUser', function ($userQuery) {
                        $userQuery->where('direction', 'like', '%'.$this->inventorySearchUserDirection.'%');
                    })
                        ->orWhereHas('responsible', function ($userQuery) {
                            $userQuery->where('direction', 'like', '%'.$this->inventorySearchUserDirection.'%');
                        });
                });
            })
            ->orderBy('ni')
            ->get();

        return view('livewire.admin.servicios.create', [
            'users' => $users,
            'filteredUsers' => $filteredUsers,
            'filteredInventories' => $filteredInventories,
        ]);
    }
}
