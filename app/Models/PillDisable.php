<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class PillDisable
 *
 * @package App\Models
 *
 * @property DateTime $date
 * @property DateTime $time
 *
 * Relations
 *
 * @property Specialist $specialist
 */
class PillDisable extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class, 'specialist_id', 'id');
    }
}
