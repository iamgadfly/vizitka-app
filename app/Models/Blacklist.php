<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Blacklist
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $specialist_id
 * @property int|null $blacklisted_id
 * @property int|null $dummy_client_id
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 * @property Client $client
 * @property Specialist $specialist
 * @property DummyClient $dummyClient
 */
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

    public function dummyClient(): BelongsTo
    {
        return $this->belongsTo(DummyClient::class, 'dummy_client_id', 'id');
    }
}
