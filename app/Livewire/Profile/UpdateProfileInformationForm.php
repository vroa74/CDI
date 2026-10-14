<?php

namespace App\Livewire\Profile;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\Features\SupportFileUploads\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * The new avatar for the user.
     *
     * @var mixed
     */
    public $photo;

    /**
     * Unique directions from database.
     *
     * @var array
     */
    public $uniqueDirections = [];

    /**
     * Unique positions from database.
     *
     * @var array
     */
    public $uniquePositions = [];

    /**
     * Prepare the component.
     */
    public function mount(): void
    {
        $this->state = Auth::user()->withoutRelations()->toArray();

        // Obtener direcciones únicas
        $this->uniqueDirections = User::whereNotNull('direction')
            ->where('direction', '!=', '')
            ->distinct()
            ->pluck('direction')
            ->sort()
            ->values()
            ->toArray();

        // Obtener puestos únicos
        $this->uniquePositions = User::whereNotNull('position')
            ->where('position', '!=', '')
            ->distinct()
            ->pluck('position')
            ->sort()
            ->values()
            ->toArray();
    }

    /**
     * Update the user's profile information.
     */
    public function updateProfileInformation(UpdatesUserProfileInformation $updater): void
    {
        $this->resetErrorBag();

        $updater->update(
            Auth::user(),
            $this->photo
                ? array_merge($this->state, ['photo' => $this->photo])
                : $this->state
        );

        $this->photo = null;
        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Delete user's profile photo.
     */
    public function deleteProfilePhoto(): void
    {
        Auth::user()->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty(): User
    {
        return Auth::user();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('livewire.profile.update-profile-information-form');
    }
}
