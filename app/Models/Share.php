<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

/**
 * Class Share
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $url
 * @property string $hash
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property DateTime $deactivated_at
 * @property string $sharable_type,
 * @property int $sharable_id
 *
 * Relations
 *
 * @property Specialist $specialist
 * @property Client $client
 */
class Share extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function specialist(): MorphTo
    {
        return $this->morphTo(Specialist::class);
    }

    public function client(): MorphTo
    {
        return $this->morphTo(Client::class);
    }
}
