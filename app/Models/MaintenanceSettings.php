<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class MaintenanceSettings
 *
 * @package App\Models
 *
 * @property int $id
 * @property boolean $finance_analytics
 * @property boolean $many_maintenances
 * @property int $specialist_id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property Collection<Maintenance> $maintenances
 */
class MaintenanceSettings extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function maintenances(): HasMany
    {
        return $this->hasMany(Maintenance::class, 'settings_id', 'id');
    }
}
