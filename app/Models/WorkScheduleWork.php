<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WorkScheduleWorks
 *
 * @package App\Models
 *
 * @property int $id
 * @property DateTime|null $start
 * @property DateTime|null $end
 * @property int $day_id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property WorkScheduleDay $day
 */
class WorkScheduleWork extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function day(): BelongsTo
    {
        return $this->belongsTo(WorkScheduleDay::class, 'day_id', 'id');
    }
}
