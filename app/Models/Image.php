<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Prunable;

/**
 * Class Image
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $url
 * @property DateTime|null $deleted_at
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Image extends Model
{
    use HasFactory, Prunable;

    protected $guarded = ['id'];

    public function prunable(): Builder
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
