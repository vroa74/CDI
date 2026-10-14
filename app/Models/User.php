<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens;

    /** @use HasFactory<UserFactory> */
    use HasFactory;

    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'rfc',
        'curp',
        'direction',
        'position',
        'sex',
        'lvl',
        'tipo',
        'status',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
        'initials',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'status' => 'boolean',
            'tipo' => 'integer',
        ];
    }

    /**
     * Get the services where this user is the first applicant.
     */

    /**
     * Get the services where this user is the second applicant.
     */
    public function servicesAsSolicitante(): HasMany
    {
        return $this->hasMany(Service::class, 'solicitante_id');
    }

    /**
     * Get the services where this user performed the service.
     */
    public function servicesAsEfectuo(): HasMany
    {
        return $this->hasMany(Service::class, 'efectuo_id');
    }

    /**
     * Get the services where this user gave approval (VºBº).
     */
    public function servicesAsVobo(): HasMany
    {
        return $this->hasMany(Service::class, 'vobo_id');
    }

    /**
     * Get the services where this user captured the service.
     */
    public function servicesAsCapturo(): HasMany
    {
        return $this->hasMany(Service::class, 'capturo');
    }

    /**
     * Get all services related to this user in any capacity.
     */
    public function allServices()
    {
        return Service::where('solicitante_id', $this->id)
            ->orWhere('efectuo_id', $this->id)
            ->orWhere('vobo_id', $this->id)
            ->orWhere('capturo', $this->id);
    }

    /**
     * Get the inventory items owned by this user.
     */
    public function inventoryItems(): HasMany
    {
        return $this->hasMany(Inventory::class, 'user_id');
    }

    /**
     * Get the inventory items where this user is responsible.
     */
    public function responsibleInventoryItems(): HasMany
    {
        return $this->hasMany(Inventory::class, 'res_id');
    }

    /**
     * Get all inventory items related to this user.
     */
    public function allInventoryItems()
    {
        return Inventory::where('user_id', $this->id)
            ->orWhere('res_id', $this->id);
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto'),
            set: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto')
        );
    }

    protected function direction(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto'),
            set: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto')
        );
    }

    protected function position(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto'),
            set: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto')
        );
    }

    protected function profilePhotoUrl(): Attribute
    {
        return Attribute::get(function (): string {
            if (! $this->hasProfilePhoto()) {
                return $this->defaultProfilePhotoUrl();
            }

            if ($this->profilePhotoDisk() === 'public') {
                return url('/storage/'.ltrim($this->profile_photo_path, '/'));
            }

            return Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path);
        });
    }

    /**
     * Responsivas creadas por este usuario.
     */
    public function responsivasCreadas()
    {
        return $this->hasMany(Responsiva::class, 'user_id');
    }

    /**
     * Responsivas donde es responsable.
     */
    public function responsivasResponsable()
    {
        return $this->hasMany(Responsiva::class, 'responsable_id');
    }

    /**
     * Responsivas donde es informático.
     */
    public function responsivasInformatica()
    {
        return $this->hasMany(Responsiva::class, 'informatica_id');
    }

    /**
     * Get the user's initials from their name.
     */
    public function getInitialsAttribute()
    {
        $name = trim($this->name);
        if (empty($name)) {
            return '?';
        }

        $words = explode(' ', $name);
        $initials = '';

        // Tomar la primera letra del primer nombre
        if (isset($words[0])) {
            $initials .= mb_strtoupper(mb_substr($words[0], 0, 1));
        }

        // Tomar la primera letra del apellido (última palabra)
        if (count($words) > 1) {
            $lastWord = end($words);
            $initials .= mb_strtoupper(mb_substr($lastWord, 0, 1));
        }

        return $initials;
    }

    /**
     * Check if the user has a profile photo.
     */
    public function hasProfilePhoto(): bool
    {
        if (empty($this->profile_photo_path)) {
            return false;
        }

        $disk = Storage::disk($this->profilePhotoDisk());

        return $disk->exists($this->profile_photo_path)
            && $disk->size($this->profile_photo_path) > 0;
    }
}
