<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

/**
 * Class WorkScheduleDay
 *
 * @package App\Models
 *
 * @property int $id
 * @property string|null $day
 * @property int|null $day_index
 * @property int $settings_id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property WorkScheduleWork $work
 * @property Collection<WorkScheduleBreak> $breaks
 * @property WorkScheduleSettings $settings
 */
class WorkScheduleDay extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function work(): HasMany
    {
        return $this->hasMany(WorkScheduleWork::class, 'day_id', 'id');
    }

    public function breaks(): HasMany
    {
        return $this->hasMany(WorkScheduleBreak::class, 'day_id', 'id');
    }

    public function settings(): BelongsTo
    {
        return $this->belongsTo(WorkScheduleSettings::class, 'settings_id', 'id');
    }
}
