<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class ContactBook
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $specialist_id
 * @property int|null $client_id
 * @property int|null $dummy_client_id
 * @property DateTime|null $deleted_at
 * @property DateTime $created_at
 * @property DateTime $updated_at
 * @property boolean $is_visible
 *
 * Relations
 *
 * @property Specialist $specialist
 * @property Client $client
 * @property DummyClient $dummyClient
 */
class ContactBook extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id');
    }

    public function dummyClient(): BelongsTo
    {
        return $this->belongsTo(DummyClient::class, 'dummy_client_id');
    }
}
