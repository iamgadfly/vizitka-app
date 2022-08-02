<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class Appointment
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $specialist_id
 * @property int|null $client_id
 * @property int|null $dummy_client_id
 * @property int $maintenance_id
 * @property DateTime $date
 * @property DateTime $time_start
 * @property DateTime $time_end
 * @property string $status
 * @property string $order_number
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 * @property Specialist $specialist
 * @property Client $client
 * @property DummyClient $dummyClient
 * @property Maintenance $maintenance
 */
class Appointment extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class, 'specialist_id', 'id');
    }

    public function client(): BelongsTo
    {
        return $this->belongsTo(Client::class, 'client_id', 'id');
    }

    public function dummyClient(): BelongsTo
    {
        return $this->belongsTo(DummyClient::class, 'dummy_client_id', 'id');
    }

    public function maintenance(): BelongsTo
    {
        return $this->belongsTo(Maintenance::class, 'maintenance_id', 'id')->withTrashed();
    }
}
