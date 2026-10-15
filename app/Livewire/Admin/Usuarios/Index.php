<?php

namespace App\Livewire\Admin\Usuarios;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;

class Index extends Component
{
    use WithFileUploads, WithPagination;

    public function boot(): void
    {
        abort_unless(auth()->user()?->can('manage-users'), 403);
    }

    public $search = '';

    public $showModal = false;

    public $editing = false;

    public $userId = null;

    // Campos del formulario
    public $name = '';

    public $rfc = '';

    public $direction = '';

    public $position = '';

    public $sex = '';

    public $lvl = '1';

    public $tipo = 3;

    public $status = true;

    public $email = '';

    public $password = '';

    public $password_confirmation = '';

    public $photo = null;

    // Propiedad para almacenar las direcciones únicas
    public $uniqueDirections = [];

    // Propiedad para almacenar las posiciones únicas
    public $uniquePositions = [];

    // Mapeo de posiciones a niveles (actualizado según la imagen proporcionada)
    protected $positionLevelMap = [
        'DIPUTADO' => '1',
        'JEFE DE GRUPO' => '10',
        'PERSONAL DE APOYO' => '1114',
        'SECRETARIO GENERAL' => '2',
        'TITULAR ORGANO INTERNO DE CONTROL' => '2',
        'ASESOR' => '3',
        'DIRECTOR' => '4',
        'SUBDIRECTOR' => '5',
        'JEFE DE DEPARTAMENTO' => '7',
        'ANALISTA ESPECIALIZADO' => '8',
        'ANALISTA' => '9',
    ];

    protected $rules = [
        'name' => 'required|string|max:255',
        'rfc' => 'nullable|string|max:13|unique:users,rfc',
        'direction' => 'nullable|string|max:250',
        'position' => 'nullable|string|max:35',
        'sex' => 'nullable|in:masculino,femenino',
        'lvl' => 'nullable|string|max:15',
        'tipo' => 'required|integer',
        'status' => 'boolean',
        'email' => 'required|email|unique:users,email',
        'password' => 'required|min:8|confirmed',
        'photo' => 'nullable|image|max:1024',
    ];

    protected $messages = [
        'name.required' => 'El nombre es obligatorio',
        'email.required' => 'El email es obligatorio',
        'email.email' => 'El email debe ser válido',
        'email.unique' => 'El email ya está registrado',
        'password.required' => 'La contraseña es obligatoria',
        'password.min' => 'La contraseña debe tener al menos 8 caracteres',
        'password.confirmed' => 'Las contraseñas no coinciden',
        'rfc.unique' => 'El RFC ya está registrado',
        'photo.image' => 'El archivo debe ser una imagen',
        'photo.max' => 'La imagen no debe superar 1MB',
    ];

    public function mount()
    {
        $this->loadUniqueDirections();
        $this->loadUniquePositions();
    }

    public function loadUniqueDirections()
    {
        $this->uniqueDirections = User::whereNotNull('direction')
            ->where('direction', '!=', '')
            ->distinct()
            ->pluck('direction')
            ->sort()
            ->values()
            ->toArray();
    }

    public function loadUniquePositions()
    {
        $this->uniquePositions = User::whereNotNull('position')
            ->where('position', '!=', '')
            ->distinct()
            ->pluck('position')
            ->sort()
            ->values()
            ->toArray();
    }

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetForm();
        $this->loadUniqueDirections();
        $this->loadUniquePositions();
        $this->showModal = true;
        $this->editing = false;
    }

    public function editUser($userId)
    {
        $user = User::findOrFail($userId);

        $this->userId = $user->id;
        $this->name = $user->name;
        $this->rfc = $user->rfc;
        $this->direction = $user->direction;
        $this->position = $user->position;
        $this->sex = $user->sex;
        $this->lvl = (string) ($user->lvl ?: '1');
        $this->tipo = $user->tipo;
        $this->status = $user->status;
        $this->email = $user->email;
        $this->password = '';
        $this->password_confirmation = '';
        $this->photo = null;

        $this->loadUniqueDirections();
        $this->loadUniquePositions();
        $this->showModal = true;
        $this->editing = true;
    }

    public function saveUser()
    {
        if ($this->editing) {
            $this->rules['email'] = 'required|email|unique:users,email,'.$this->userId;
            $this->rules['rfc'] = 'nullable|string|max:13|unique:users,rfc,'.$this->userId;
            $this->rules['password'] = 'nullable|min:8|confirmed';
        } else {
            $this->rules['password'] = 'required|min:8|confirmed';
        }

        $this->validate();

        $validatedData = $this->only(['name', 'rfc', 'direction', 'position', 'sex', 'lvl', 'tipo', 'email']);
        $validatedData['status'] = $this->status ? 1 : 0;

        if ($this->password) {
            $validatedData['password'] = Hash::make($this->password);
        }

        if ($this->editing) {
            $user = User::find($this->userId);
            $user->update($validatedData);

            // Actualizar foto de perfil si se proporcionó una nueva
            if ($this->photo) {
                $user->updateProfilePhoto($this->photo);
            }

            $this->dispatch('showMessage', 'Usuario actualizado correctamente.');
        } else {
            $user = User::create($validatedData);

            // Establecer foto de perfil si se proporcionó una
            if ($this->photo) {
                $user->updateProfilePhoto($this->photo);
            }

            $this->dispatch('showMessage', 'Usuario creado correctamente.');
        }

        // Recargar las direcciones únicas después de guardar
        $this->loadUniqueDirections();
        $this->loadUniquePositions();
        $this->closeModal();
    }

    public function deleteUser($userId)
    {
        User::find($userId)->delete();
        session()->flash('message', 'Usuario eliminado correctamente.');
    }

    public function toggleStatus($userId)
    {
        $user = User::find($userId);
        $user->update(['status' => ! $user->status]);
        $status = $user->status ? 'activo' : 'inactivo';
        $this->dispatch('showMessage', "El usuario ahora está $status.");
    }

    public function toggleSex($userId)
    {
        $user = User::find($userId);
        $newSex = $user->sex === 'masculino' ? 'femenino' : 'masculino';
        $user->update(['sex' => $newSex]);
        session()->flash('message', 'Sexo del usuario actualizado.');
    }

    public function deleteProfilePhoto()
    {
        if ($this->editing && $this->userId) {
            $user = User::find($this->userId);
            if ($user && $user->profile_photo_path) {
                $user->deleteProfilePhoto();
                session()->flash('message', 'Foto de perfil eliminada correctamente.');
            }
        }
    }

    public function updatedDirection()
    {
        // Si se selecciona "Agregar nueva dirección", limpiar el valor
        if ($this->direction === '__new__') {
            $this->direction = '';
        }
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    private function resetForm()
    {
        $this->userId = null;
        $this->name = '';
        $this->rfc = '';
        $this->direction = '';
        $this->position = '';
        $this->sex = '';
        $this->lvl = '1';
        $this->tipo = 3;
        $this->status = true;
        $this->email = '';
        $this->password = '';
        $this->password_confirmation = '';
        $this->photo = null;
        $this->editing = false;
        $this->resetValidation();
    }

    public function render()
    {
        $users = User::query()
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                        ->orWhere('email', 'like', '%'.$this->search.'%')
                        ->orWhere('rfc', 'like', '%'.$this->search.'%')
                        ->orWhere('direction', 'like', '%'.$this->search.'%')
                        ->orWhere('position', 'like', '%'.$this->search.'%')
                        ->orWhere('sex', 'like', '%'.$this->search.'%')
                        ->orWhere('lvl', 'like', '%'.$this->search.'%');
                });
            })
            ->orderBy('id')
            ->paginate(10);

        return view('livewire.admin.usuarios.index', [
            'users' => $users,
            'uniqueDirections' => $this->uniqueDirections,
            'uniquePositions' => $this->uniquePositions,
        ]);
    }
}
