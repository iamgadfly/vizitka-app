<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * Class DummyClient
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property string|null $surname
 * @property string $phone_number
 * @property float $discount
 * @property int|null $avatar_id
 * @property int $specialist_id
 * @property string|null $notes
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property Image $avatar
 * @property Specialist $specialist
 */
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
