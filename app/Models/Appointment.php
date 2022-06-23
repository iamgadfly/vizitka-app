<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        return $this->belongsTo(Maintenance::class, 'maintenance_id', 'id');
    }
}
