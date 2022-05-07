<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DummyClient extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function avatar()
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'id');
    }
}
