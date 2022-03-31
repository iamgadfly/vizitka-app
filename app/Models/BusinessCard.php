<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function specialist(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }
}
