<?php

namespace App\Models;

use App\Services\GeocodeService;
use DateTime;
use Geocoder\Collection;
use Geocoder\Exception\Exception;
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

    public static function boot()
    {
        parent::boot();

        static::updating(function (BusinessCard $model) {
            self::setCoordinates($model);
        });

        static::creating(function (BusinessCard $model) {
            self::setCoordinates($model);
        });

    }

    private static function setCoordinates(BusinessCard $model)
    {
        if (is_null($model->address)) {
            $model->latitude = 0;
            $model->longitude = 0;
            return;
        }
        try {
            $coordinates = GeocodeService::fromAddress($model->address);
        } catch (Exception) {
            $model->latitude = 0;
            $model->longitude = 0;
            return;
        }
        if ($coordinates->isEmpty()) {
            $model->latitude = 0;
            $model->longitude = 0;
            return;
        }
        $coordinates = $coordinates->first()->getCoordinates();
        $model->latitude = $coordinates->getLatitude();
        $model->longitude = $coordinates->getLongitude();
    }
}
