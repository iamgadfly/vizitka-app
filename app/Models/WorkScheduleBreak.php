<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkScheduleBreak extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function day(): BelongsTo
    {
        return $this->belongsTo(WorkScheduleDay::class, 'day_id', 'id');
    }
}
