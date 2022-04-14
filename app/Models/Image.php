<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

class Image extends Model
{
    use HasFactory, Prunable;

    protected $guarded = ['id'];

    public function prunable()
    {
        return static::query()->where('deleted_at', '<=', now()->subDay());
    }

    public function pruning()
    {
        \Storage::disk('public')->delete($this->url);
    }

    protected $casts = [
        'deleted_at' => 'datetime'
    ];
}
