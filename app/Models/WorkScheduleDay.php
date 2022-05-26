<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

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
