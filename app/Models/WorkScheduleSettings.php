<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Collection;

/**
 * Class WorkScheduleSettings
 *
 * @package App\Models
 *
 * @property int $id
 * @property boolean $smart_schedule
 * @property boolean $confirmation
 * @property int $cancel_appointment
 * @property int $limit_before
 * @property int $limit_after
 * @property int $specialist_id
 * @property string $type
 * @property string|null $break_type
 * @property DateTime $start_from
 * @property int|null $workdays_count
 * @property int|null $weekdays_count
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property Collection<WorkScheduleDay> $day
 * @property Specialist $specialist
 */
class WorkScheduleSettings extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'weekends' => 'array'
    ];

    public function day(): HasMany
    {
        return $this->hasMany(WorkScheduleDay::class, 'settings_id', 'id');
    }

    public function specialist(): HasOne
    {
        return $this->hasOne(Specialist::class, 'id', 'specialist_id');
    }
}
