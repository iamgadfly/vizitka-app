<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DummyBusinessCard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'id');
    }
}
