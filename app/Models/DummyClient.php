<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class DummyClient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'id');
    }


    public function specialist(): HasOne
    {
        return $this->hasOne(Specialist::class, 'id', 'specialist_id');
    }
}
