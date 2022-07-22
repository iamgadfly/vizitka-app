<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Onboarding
 *
 * @package App\Models
 *
 * @property int $id
 * @property string $title
 * @property string $description
 * @property string $text_button
 * @property string $image
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class Onboarding extends Model
{
    use HasFactory;

    protected $guarded = ['id'];
}
