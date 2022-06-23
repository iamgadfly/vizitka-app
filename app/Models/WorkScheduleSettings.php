<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
