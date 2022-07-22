<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class ContactData
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $client_id
 * @property int $specialist_id
 * @property string|null $phone_number
 * @property string|null $notes
 * @property string|null $name
 * @property string|null $surname
 * @property float $discount
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property Client $client
 */
class ContactData extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }
}
