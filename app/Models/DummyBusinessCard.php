<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class DummyBusinessCard
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $client_id
 * @property string $phone_number
 * @property string|null $name
 * @property string|null $surname
 * @property string|null $title
 * @property int|null $avatar_id
 * @property string|null $about
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property Client $client
 * @property Image $avatar
 */
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
