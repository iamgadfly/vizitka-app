<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Class BusinessCard
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $specialist_id
 * @property string $background_image
 * @property string $title
 * @property string $about
 * @property string $address
 * @property string $placement
 * @property string $floor
 * @property double $latitude
 * @property double $longitude
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property Specialist $specialist
 */
class BusinessCard extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function specialist(): BelongsTo
    {
        return $this->belongsTo(Specialist::class, 'specialist_id');
    }
}
