<x-form-section submit="updateProfileInformation">
    <x-slot name="title">
        {{ __('Información del Perfil') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Actualiza la información de tu perfil y dirección de correo electrónico.') }}
    </x-slot>

    <x-slot name="form">
        <!-- Profile Photo -->
        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
            <div x-data="{photoName: null, photoPreview: null}" class="col-span-6 sm:col-span-4">
                <!-- Profile Photo File Input -->
                <input type="file" id="photo" class="hidden"
                            wire:model.live="photo"
                            x-ref="photo"
                            x-on:change="
                                    photoName = $refs.photo.files[0].name;
                                    const reader = new FileReader();
                                    reader.onload = (e) => {
                                        photoPreview = e.target.result;
                                    };
                                    reader.readAsDataURL($refs.photo.files[0]);
                            " />

                <x-label for="photo" value="{{ __('Foto') }}" />

                <!-- Current Profile Photo -->
                <div class="mt-2" x-show="! photoPreview">
                    <img src="{{ $this->user->profile_photo_url }}" alt="{{ $this->user->name }}" class="rounded-full size-20 object-cover">
                </div>

                <!-- New Profile Photo Preview -->
                <div class="mt-2" x-show="photoPreview" style="display: none;">
                    <span class="block rounded-full size-20 bg-cover bg-no-repeat bg-center"
                          x-bind:style="'background-image: url(\'' + photoPreview + '\');'">
                    </span>
                </div>

                <x-secondary-button class="mt-2 me-2" type="button" x-on:click.prevent="$refs.photo.click()">
                    {{ __('Seleccionar Nueva Foto') }}
                </x-secondary-button>

                @if ($this->user->profile_photo_path)
                    <x-secondary-button type="button" class="mt-2" wire:click="deleteProfilePhoto">
                        {{ __('Eliminar Foto') }}
                    </x-secondary-button>
                @endif

                <x-input-error for="photo" class="mt-2" />
            </div>
        @endif

        <!-- Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="name" value="{{ __('Nombre') }}" />
            <x-input id="name" type="text" class="mt-1 block w-full" wire:model="state.name" required autocomplete="name" />
            <x-input-error for="name" class="mt-2" />
        </div>

        <!-- RFC -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="rfc" value="{{ __('RFC') }}" />
            <x-input id="rfc" type="text" class="mt-1 block w-full" wire:model="state.rfc" maxlength="13" autocomplete="off" />
            <x-input-error for="rfc" class="mt-2" />
        </div>

        <!-- CURP -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="curp" value="{{ __('CURP') }}" />
            <x-input id="curp" type="text" class="mt-1 block w-full" wire:model="state.curp" maxlength="18" autocomplete="off" />
            <x-input-error for="curp" class="mt-2" />
        </div>

        <!-- Direction -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="direction" value="{{ __('Dirección') }}" />
            <select id="direction" class="mt-1 block w-full border-gray-700 bg-gray-800 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="state.direction">
                <option value="">Seleccionar dirección...</option>
                @foreach($uniqueDirections as $direction)
                    <option value="{{ $direction }}">{{ $direction }}</option>
                @endforeach
            </select>
            <x-input-error for="direction" class="mt-2" />
        </div>

        <!-- Position -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="position" value="{{ __('Puesto') }}" />
            <select id="position" class="mt-1 block w-full border-gray-700 bg-gray-800 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="state.position">
                <option value="">Seleccionar puesto...</option>
                @foreach($uniquePositions as $position)
                    <option value="{{ $position }}">{{ $position }}</option>
                @endforeach
            </select>
            <x-input-error for="position" class="mt-2" />
        </div>

        <!-- Sex -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="sex" value="{{ __('Sexo') }}" />
            <select id="sex" class="mt-1 block w-full border-gray-700 bg-gray-800 text-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" wire:model="state.sex">
                <option value="">Seleccionar...</option>
                <option value="masculino">Masculino</option>
                <option value="femenino">Femenino</option>
            </select>
            <x-input-error for="sex" class="mt-2" />
        </div>

        <!-- Level -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="lvl" value="{{ __('Nivel') }}" />
            <x-input id="lvl" type="text" class="mt-1 block w-full" wire:model="state.lvl" maxlength="10" autocomplete="off" />
            <x-input-error for="lvl" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="tipo" value="{{ __('Tipo de Usuario') }}" />
            <select id="tipo" class="mt-1 block w-full border-gray-700 bg-gray-800 text-gray-300 rounded-md shadow-sm opacity-75 cursor-not-allowed" disabled>
                <option value="1" @selected((int) $this->user->tipo === 1)>Administrador</option>
                <option value="2" @selected((int) $this->user->tipo === 2)>Supervisor</option>
                <option value="3" @selected((int) $this->user->tipo === 3)>Usuario</option>
            </select>
        </div>

        <div class="col-span-6 sm:col-span-4">
            <label for="status" class="flex items-center">
                <input id="status" type="checkbox" class="rounded border-gray-700 bg-gray-800 text-indigo-600 shadow-sm opacity-75 cursor-not-allowed" @checked($this->user->status) disabled>
                <span class="ml-2 text-sm text-gray-300">{{ __('Usuario Activo') }}</span>
            </label>
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-4">
            <x-label for="email" value="{{ __('Correo electrónico') }}" />
            <x-input id="email" type="email" class="mt-1 block w-full" wire:model="state.email" required autocomplete="username" />
            <x-input-error for="email" class="mt-2" />

            @if (Laravel\Fortify\Features::enabled(Laravel\Fortify\Features::emailVerification()) && ! $this->user->hasVerifiedEmail())
                <p class="text-sm mt-2">
                    {{ __('Tu dirección de correo electrónico no está verificada.') }}

                    <button type="button" class="underline text-sm text-gray-300 hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" wire:click.prevent="sendEmailVerification">
                        {{ __('Haz clic aquí para reenviar el correo de verificación.') }}
                    </button>
                </p>

                @if ($this->verificationLinkSent)
                    <p class="mt-2 font-medium text-sm text-green-600">
                        {{ __('Se ha enviado un nuevo enlace de verificación a tu dirección de correo electrónico.') }}
                    </p>
                @endif
            @endif
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('¡El perfil se guardó con éxito!') }}
        </x-action-message>

        <x-button wire:loading.attr="disabled" wire:target="photo">
            {{ __('Guardar') }}
        </x-button>
    </x-slot>
</x-form-section> 