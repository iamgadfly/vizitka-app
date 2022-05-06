<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkScheduleSettings extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'weekends' => 'array'
    ];

    public function day()
    {
        return $this->hasMany(WorkScheduleDay::class, 'settings_id', 'id');
    }

    public function specialist()
    {
        return $this->hasOne(Specialist::class, 'id', 'specialist_id');
    }
}
