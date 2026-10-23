<?php

namespace App\Livewire\Admin\Inventario;

use App\Models\Inventory;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class UserInv extends Component
{
    use WithPagination;

    protected $listeners = [
        'asignarResguardante',
        'asignacionResguardanteCancelada',
        'refresh' => '$refresh',
    ];

    public $resguardanteFilter = '';

    public function updatedResguardanteFilter()
    {
        $this->resetPage('usersPage');
        $this->resetPage('inventoryPage');
    }

    public function render()
    {
        $users = User::query()
            ->where('status', true)
            ->when($this->resguardanteFilter, function ($query) {
                $valor = strtolower($this->resguardanteFilter);
                $query->whereRaw('LOWER(name) LIKE ?', ["%{$valor}%"]);
            })
            ->orderBy('id')
            ->paginate(30, ['*'], 'usersPage');

        $inventories = Inventory::query()
            ->with(['assignedUser', 'responsible'])
            ->when($this->resguardanteFilter, function ($query) {
                $valor = strtolower($this->resguardanteFilter);
                $query->where(function ($subQuery) use ($valor) {
                    $subQuery->whereRaw('LOWER(resguardante) LIKE ?', ["%{$valor}%"])
                        ->orWhereHas('responsible', function ($relationQuery) use ($valor) {
                            $relationQuery->whereRaw('LOWER(name) LIKE ?', ["%{$valor}%"]);
                        });
                });
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10, ['*'], 'inventoryPage');

        return view('livewire.admin.inventario.user-inv', [
            'users' => $users,
            'inventories' => $inventories,
        ]);
    }
}
