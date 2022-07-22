<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class ActivityKind
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class ActivityKind extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
