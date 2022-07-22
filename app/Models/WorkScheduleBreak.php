<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class WorkScheduleBreak
 *
 * @package App\Models
 *
 * @property int $id
 * @property DateTime $start
 * @property DateTime $end
 * @property int $day_id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 * @property WorkScheduleDay $day
 */
class WorkScheduleBreak extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function day(): BelongsTo
    {
        return $this->belongsTo(WorkScheduleDay::class, 'day_id', 'id');
    }
}
