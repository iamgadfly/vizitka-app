<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BusinessCard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }
}
