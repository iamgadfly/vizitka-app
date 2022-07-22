<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Report
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $name
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Report extends Model
{
    use HasFactory;
}
