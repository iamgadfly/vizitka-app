<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MaintenanceSettings extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'settings_id', 'id');
    }
}
