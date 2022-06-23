<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Blacklist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'blacklisted_id', 'id');
    }

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class, 'specialist_id', 'id');
    }
}
