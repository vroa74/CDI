<?php

namespace App\Actions\Fortify;

use App\Models\User;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  array<string, mixed>  $input
     */
    public function update(User $user, array $input): void
    {
        $validated = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'rfc' => ['nullable', 'string', 'max:13', Rule::unique('users', 'rfc')->ignore($user->id)],
            'curp' => ['sometimes', 'required', 'string', 'max:18', Rule::unique('users', 'curp')->ignore($user->id)],
            'direction' => ['nullable', 'string', 'max:250'],
            'position' => ['nullable', 'string', 'max:35'],
            'sex' => ['nullable', Rule::in(['masculino', 'femenino'])],
            'lvl' => ['nullable', 'string', 'max:10'],
            'photo' => ['nullable', 'image', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if (isset($validated['photo'])) {
            $user->updateProfilePhoto($validated['photo']);
        }

        $profileInformation = Arr::only($validated, [
            'name',
            'email',
            'rfc',
            'curp',
            'direction',
            'position',
            'sex',
            'lvl',
        ]);

        if ($validated['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $profileInformation['email_verified_at'] = null;
            $user->forceFill($profileInformation)->save();
            $user->sendEmailVerificationNotification();
        } else {
            $user->forceFill($profileInformation)->save();
        }
    }
}
