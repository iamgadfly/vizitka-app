<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
 */
class Share extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
