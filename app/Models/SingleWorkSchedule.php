<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class SingleWorkSchedule
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $day_id
 * @property DateTime $date
 * @property DateTime $start
 * @property DateTime $end
 * @property boolean $is_break
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property WorkScheduleDay $day
 */
class SingleWorkSchedule extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function day(): BelongsTo
    {
        return $this->belongsTo(WorkScheduleDay::class, 'day_id', 'id');
    }
}
