<?php

namespace App\Models;

use Database\Factories\ServiceFactory;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    /** @use HasFactory<ServiceFactory> */
    use HasFactory;

    protected $fillable = [
        'id_s',
        'F_serv',
        'solicitante_id',
        'efectuo_id',
        'vobo_id',
        'obj_sol',
        'actividades',
        'mantenimiento',
        'observaciones',
        'correctivo',
        'preventivo',
        'transparencia',
        'a_tec',
        'web_ins',
        'print',
        'email',
        'tel',
        'sol_ser',
        'oficio',
        'calendario',
        'capturo',
        'status',
        'impressions',
    ];

    protected $casts = [
        'F_serv' => 'date',
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
        'status' => 'boolean',
        'impressions' => 'boolean',
    ];

    protected $with = ['solicitante', 'efectuo', 'vobo', 'capturo'];

    protected static function booted(): void
    {
        static::creating(function (Service $service): void {
            if (blank($service->id_s)) {
                $service->id_s = now()->format('Y-m-d/H:i:s');
            }
        });
    }

    // Relaciones con usuarios

    public function solicitante()
    {
        return $this->belongsTo(User::class, 'solicitante_id');
    }

    public function efectuo()
    {
        return $this->belongsTo(User::class, 'efectuo_id');
    }

    public function vobo()
    {
        return $this->belongsTo(User::class, 'vobo_id');
    }

    public function capturo()
    {
        return $this->belongsTo(User::class, 'capturo');
    }

    public function servicesAsSolicitante()
    {
        return $this->hasMany(Service::class, 'solicitante_id');
    }

    public function servicesAsEfectuo()
    {
        return $this->hasMany(Service::class, 'efectuo_id');
    }

    public function servicesAsVobo()
    {
        return $this->hasMany(Service::class, 'vobo_id');
    }

    public function servicesAsCapturo()
    {
        return $this->hasMany(Service::class, 'capturo');
    }

    /**
     * Get the photos associated with this service.
     */
    public function photos()
    {
        return $this->hasMany(ServicePhoto::class);
    }

    /**
     * Get the inventory items associated with this service.
     */
    public function inventories()
    {
        return $this->belongsToMany(Inventory::class, 'service_inventory')
            ->withPivot(['user_id', 'service_date'])
            ->withTimestamps();
    }

    protected function actividades(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto'),
            set: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto')
        );
    }

    protected function observaciones(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto'),
            set: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto')
        );
    }

    protected function obj_sol(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto'),
            set: fn ($value) => mb_convert_encoding($value, 'UTF-8', 'auto')
        );
    }
}
