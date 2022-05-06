<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkScheduleDay extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function work()
    {
        return $this->hasMany(WorkScheduleWork::class, 'day_id', 'id');
    }

    public function breaks()
    {
        return $this->hasMany(WorkScheduleBreak::class, 'day_id', 'id');
    }

    public function settings()
    {
        return $this->belongsTo(WorkScheduleSettings::class, 'settings_id', 'id');
    }
}
