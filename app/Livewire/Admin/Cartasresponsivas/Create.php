<?php

namespace App\Livewire\Admin\Cartasresponsivas;

use App\Models\Inventory;
use App\Models\InventoryPhoto;
use App\Models\Responsiva;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Livewire\Attributes\Locked;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Create extends Component
{
    use WithFileUploads, WithPagination;

    protected $listeners = ['refreshComponent' => '$refresh'];

    public $user_id;

    #[Locked]
    public ?int $responsivaId = null;

    public $responsable_id;

    public $informatica_id;

    public $fecha;

    public $codigo;

    public $auditoria = false;

    public $users;

    public $inventories;

    #[Locked]
    public $selected_inventories = [];

    // Modal de inventarios
    public $showInventoryModal = false;

    public $inventorySearch = '';

    public $filteredInventories = [];

    // Descripciones
    public $inventoryDescriptions = [];

    // Fotos
    public $selectedInventoryForPhotos = null;

    public $photo = [];

    public $photoPreview = [];

    public $photoDescription = [];

    public $openPhotoAccordion = [];

    public $showAddPhotoForm = [];

    public $showPhotoModal = [];

    public $modalPhoto = [];

    public $modalPhotoPreview = [];

    public $modalPhotoDescription = [];

    public $showUserModal = false;

    public $userModalTarget = null; // 'user_id', 'responsable_id', 'informatica_id'

    public $userModalFilter = '';

    public $userModalList = [];

    public $activePhotoFormId = null;

    protected $rules = [
        'user_id' => 'required|exists:users,id',
        'responsable_id' => 'required|exists:users,id',
        'informatica_id' => 'required|exists:users,id',
        'fecha' => 'required|date',
        'codigo' => 'required|string|max:20|unique:responsivas,codigo',
        'auditoria' => 'boolean',
        'selected_inventories' => 'required|array|min:1',
        'selected_inventories.*' => 'exists:inventories,id',
    ];

    public function mount(?int $id = null): void
    {
        $this->users = User::orderBy('name')->get();
        $this->inventories = Inventory::where('status', true)->orderBy('id', 'desc')->get();
        $this->filteredInventories = $this->inventories;
        $this->fecha = now()->format('Y-m-d');
        $this->codigo = 'CR/'.now()->format('Ymd/His');

        if ($id !== null) {
            Gate::authorize('manage-inventory-photos');
            $responsiva = Responsiva::with('inventoryResponsivas')->findOrFail($id);
            $this->responsivaId = $responsiva->id;
            $this->user_id = $responsiva->user_id;
            $this->responsable_id = $responsiva->responsable_id;
            $this->informatica_id = $responsiva->informatica_id;
            $this->fecha = $responsiva->fecha?->format('Y-m-d');
            $this->codigo = $responsiva->codigo;
            $this->auditoria = $responsiva->auditoria;
            $this->selected_inventories = $responsiva->inventoryResponsivas->pluck('inventory_id')->all();
            $this->inventoryDescriptions = $responsiva->inventoryResponsivas->pluck('description', 'inventory_id')->all();
            $this->openPhotoAccordion = array_fill_keys($this->selected_inventories, true);
        }
    }

    public static function userMatchesTarget(User $user, string $target): bool
    {
        $direction = Str::upper(Str::squish(Str::ascii($user->direction ?? '')));

        return match ($target) {
            'user_id' => true,
            'responsable_id' => $direction === 'ORGANO INTERNO DE CONTROL',
            'informatica_id' => $direction === 'DIRECCION DE INFORMATICA',
            default => false,
        };
    }

    public function usersForTarget(string $target): Collection
    {
        return collect($this->users)->filter(fn (User $user): bool => self::userMatchesTarget($user, $target));
    }

    public function openInventoryModal()
    {
        $this->showInventoryModal = true;
        $this->inventorySearch = '';
        $this->resetPage();
    }

    public function closeInventoryModal()
    {
        $this->showInventoryModal = false;
        $this->inventorySearch = '';
        $this->resetPage();
    }

    public function updatedInventorySearch()
    {
        $this->resetPage();
    }

    public function selectInventory($inventoryId)
    {
        if (! in_array($inventoryId, $this->selected_inventories)) {
            $this->selected_inventories[] = $inventoryId;
            $this->inventoryDescriptions[$inventoryId] = '';
            $this->openPhotoAccordion[$inventoryId] = true;
            $this->showAddPhotoForm[$inventoryId] = true;
            $this->openPhotoModal($inventoryId);
        }
        $this->closeInventoryModal();
    }

    public function removeInventory($inventoryId)
    {
        $this->selected_inventories = array_filter($this->selected_inventories, function ($id) use ($inventoryId) {
            return $id != $inventoryId;
        });
        unset($this->inventoryDescriptions[$inventoryId]);
        if ($this->selectedInventoryForPhotos == $inventoryId) {
            $this->selectedInventoryForPhotos = null;
        }
    }

    public function getSelectedInventoryItems()
    {
        $items = Inventory::whereIn('id', $this->selected_inventories)->get()->keyBy('id');

        return collect($this->selected_inventories)->map(fn ($id) => $items[$id])->filter();
    }

    public function getFilteredInventoriesPaginated()
    {
        $query = Inventory::where('status', true);

        if (! empty($this->inventorySearch)) {
            $search = strtolower($this->inventorySearch);
            $query->where(function ($q) use ($search) {
                $q->whereRaw('LOWER(articulo) LIKE ?', ['%'.$search.'%'])
                    ->orWhereRaw('LOWER(ni) LIKE ?', ['%'.$search.'%'])
                    ->orWhereRaw('LOWER(ns) LIKE ?', ['%'.$search.'%'])
                    ->orWhereRaw('LOWER(marca) LIKE ?', ['%'.$search.'%'])
                    ->orWhereRaw('LOWER(modelo) LIKE ?', ['%'.$search.'%']);
            });
        }

        return $query->orderBy('id', 'desc')->paginate(10);
    }

    // --- Fotos ---
    public function selectInventoryForPhotos($inventoryId)
    {
        $this->openPhotoAccordion[$inventoryId] = true;
        $this->selectedInventoryForPhotos = $inventoryId;
        $this->resetPhotoForm($inventoryId);
        $this->showAddPhotoForm[$inventoryId] = true;
    }

    public function resetPhotoForm($inventoryId)
    {
        $this->photo[$inventoryId] = null;
        $this->photoPreview[$inventoryId] = null;
        $this->photoDescription[$inventoryId] = '';
    }

    public function updatedPhoto($value, $name)
    {
        // $name is like 'photo.5' where 5 is the inventory id
        $parts = explode('.', $name);
        $inventoryId = $parts[1] ?? null;
        if ($inventoryId && isset($this->photo[$inventoryId])) {
            $this->photoPreview[$inventoryId] = $this->photo[$inventoryId]->temporaryUrl();
        }
    }

    public function addPhoto($inventoryId)
    {
        $this->authorizeInventoryPhotoAction((int) $inventoryId);

        $this->validate([
            'photo.'.$inventoryId => 'required|image|max:2048',
            'photoDescription.'.$inventoryId => 'nullable|string|max:255',
        ]);
        $path = $this->photo[$inventoryId]->store('inventory_photos', 'public');
        InventoryPhoto::create([
            'inventory_id' => $inventoryId,
            'path' => $path,
            'description' => $this->photoDescription[$inventoryId],
        ]);
        $this->photo[$inventoryId] = null;
        $this->photoPreview[$inventoryId] = null;
        $this->photoDescription[$inventoryId] = '';
        $this->showAddPhotoForm[$inventoryId] = false;
        session()->flash('message', 'Foto agregada correctamente.');
    }

    public function showAddPhotoForm($inventoryId)
    {
        $this->showAddPhotoForm[$inventoryId] = true;
    }

    private function authorizeInventoryPhotoAction(int $inventoryId): void
    {
        Gate::authorize('manage-inventory-photos');
        abort_unless(in_array($inventoryId, array_map('intval', $this->selected_inventories), true), 403);
    }

    public function deletePhoto($photoId)
    {
        Gate::authorize('manage-inventory-photos');

        $photo = InventoryPhoto::whereIn('inventory_id', $this->selected_inventories)->findOrFail($photoId);
        if ($photo) {
            Storage::disk('public')->delete($photo->path);
            $photo->delete();
        }
    }

    public function getPhotosForSelectedInventory()
    {
        if ($this->selectedInventoryForPhotos) {
            return InventoryPhoto::where('inventory_id', $this->selectedInventoryForPhotos)->get();
        }

        return collect();
    }

    public function openPhotoModal($inventoryId)
    {
        $this->showPhotoModal = array_merge($this->showPhotoModal, [$inventoryId => true]);
        $this->modalPhoto[$inventoryId] = null;
        $this->modalPhotoPreview[$inventoryId] = null;
        $this->modalPhotoDescription[$inventoryId] = '';
        $this->dispatch('$refresh');
    }

    public function closePhotoModal($inventoryId)
    {
        $this->showPhotoModal[$inventoryId] = false;
        $this->modalPhoto[$inventoryId] = null;
        $this->modalPhotoPreview[$inventoryId] = null;
        $this->modalPhotoDescription[$inventoryId] = '';
    }

    public function updatedModalPhoto($value, $name)
    {
        $parts = explode('.', $name);
        $inventoryId = $parts[1] ?? null;
        if ($inventoryId && isset($this->modalPhoto[$inventoryId])) {
            $this->modalPhotoPreview[$inventoryId] = $this->modalPhoto[$inventoryId]->temporaryUrl();
        }
    }

    public function openPhotoForm($inventoryId)
    {
        $this->authorizeInventoryPhotoAction((int) $inventoryId);

        $this->activePhotoFormId = $inventoryId;
        $this->modalPhoto[$inventoryId] = null;
        $this->modalPhotoPreview[$inventoryId] = null;
        $this->modalPhotoDescription[$inventoryId] = '';
    }

    public function closePhotoForm()
    {
        $this->activePhotoFormId = null;
    }

    public function savePhotoFromModal($inventoryId)
    {
        $this->authorizeInventoryPhotoAction((int) $inventoryId);

        $this->validate([
            'modalPhoto.'.$inventoryId => 'required|image|max:2048',
            'modalPhotoDescription.'.$inventoryId => 'nullable|string|max:255',
        ]);
        $path = $this->modalPhoto[$inventoryId]->store('inventory_photos', 'public');
        InventoryPhoto::create([
            'inventory_id' => $inventoryId,
            'path' => $path,
            'description' => $this->modalPhotoDescription[$inventoryId],
        ]);
        $this->closePhotoForm();
        session()->flash('message', 'Foto agregada correctamente.');
    }

    public function openUserModal($target)
    {
        logger('openUserModal called', ['target' => $target]);
        $this->userModalTarget = $target;
        $this->userModalFilter = '';
        $this->showUserModal = true;
        $this->updateUserModalList();
    }

    public function closeUserModal()
    {
        $this->showUserModal = false;
        $this->userModalTarget = null;
        $this->userModalFilter = '';
        $this->userModalList = [];
    }

    public function updatedUserModalFilter()
    {
        $this->updateUserModalList();
    }

    public function updateUserModalList()
    {
        $search = Str::lower(trim($this->userModalFilter));
        $this->userModalList = $this->usersForTarget($this->userModalTarget ?? '')
            ->filter(fn (User $user): bool => $search === '' || Str::contains(Str::lower($user->name.' '.$user->email), $search))
            ->take(20)->values();
    }

    public function selectUserFromModal($userId)
    {
        $user = User::find($userId);
        if ($user && self::userMatchesTarget($user, $this->userModalTarget ?? '')) {
            $this->{$this->userModalTarget} = $user->id;
        }
        $this->closeUserModal();
    }

    public function save()
    {
        Gate::authorize('manage-inventory-photos');

        Log::info('Livewire save ejecutado', [
            'user_id' => $this->user_id,
            'responsable_id' => $this->responsable_id,
            'informatica_id' => $this->informatica_id,
            'fecha' => $this->fecha,
            'codigo' => $this->codigo,
            'auditoria' => $this->auditoria,
            'selected_inventories' => $this->selected_inventories,
        ]);
        $rules = $this->rules;
        $rules['codigo'] = ['required', 'string', 'max:20', Rule::unique('responsivas', 'codigo')->ignore($this->responsivaId)];
        $this->validate($rules);

        $selectedUsers = User::whereIn('id', [$this->user_id, $this->responsable_id, $this->informatica_id])->get()->keyBy('id');
        foreach (['user_id', 'responsable_id', 'informatica_id'] as $target) {
            $user = $selectedUsers->get($this->{$target});
            if (! $user || ! self::userMatchesTarget($user, $target)) {
                throw ValidationException::withMessages([
                    $target => match ($target) {
                        'responsable_id' => 'El responsable debe pertenecer a Órgano Interno de Control.',
                        'informatica_id' => 'Seleccione un usuario válido de Dirección de Informática.',
                        default => 'Seleccione un usuario válido para generar la carta.',
                    },
                ]);
            }
        }

        DB::transaction(function (): void {
            $responsiva = $this->responsivaId === null ? new Responsiva : Responsiva::findOrFail($this->responsivaId);
            $responsiva->fill([
                'user_id' => $this->user_id,
                'responsable_id' => $this->responsable_id,
                'informatica_id' => $this->informatica_id,
                'fecha' => $this->fecha,
                'codigo' => $this->codigo,
                'auditoria' => $this->auditoria,
            ]);
            $responsiva->save();

            foreach ($this->selected_inventories as $inventory_id) {
                $responsiva->inventoryResponsivas()->updateOrCreate(['inventory_id' => $inventory_id], [
                    'description' => $this->inventoryDescriptions[$inventory_id] ?? null,
                ]);
            }

            $responsiva->inventoryResponsivas()->whereNotIn('inventory_id', $this->selected_inventories)->delete();
        });

        session()->flash('message', $this->responsivaId === null ? 'Carta responsiva creada correctamente.' : 'Carta responsiva actualizada correctamente.');
        $this->reset(['user_id', 'responsable_id', 'informatica_id', 'fecha', 'codigo', 'auditoria', 'selected_inventories', 'inventoryDescriptions', 'selectedInventoryForPhotos', 'photo', 'photoDescription']);

        return $this->redirectRoute('cartasresponsivas.index');
    }

    public function render()
    {
        return view('livewire.admin.cartasresponsivas.create');
    }
}
