<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Maintenance
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $settings_id
 * @property string $title
 * @property integer|null $price
 * @property integer $duration
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property MaintenanceSettings $settings
 */
class Maintenance extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function settings(): BelongsTo
    {
        return $this->belongsTo(MaintenanceSettings::class, 'settings_id', 'id');
    }
}
