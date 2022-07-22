<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Maintenance
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $settings_id
 * @property int $specialist_id
 * @property string $title
 * @property integer|null $price
 * @property integer $duration
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Maintenance extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
