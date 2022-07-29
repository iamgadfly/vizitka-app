<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Deivce
 *
 * @package App\Models
 *
 * @property string $device_id
 * @property string $pin
 *
 * Relations
 *
 * @property User $user
 */
class Device extends Model
{
    use HasFactory;

    protected $guarded = ['id'];


    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
