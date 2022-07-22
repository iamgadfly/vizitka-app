<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class AdminUser
 *
 * @package App\Models
 *
 * @property int $id
 * @property string|null $avatar
 * @property string $email
 * @property string $password
 * @property string|null $remember_token
 * @property DateTime|null $email_verified_at
 * @property DateTime $created_at
 * @property DateTime $updated_at
 */
class AdminUser extends Authenticatable
{
    use HasFactory;

    protected $fillable = [
        'name',
        'email',
        'password'
    ];

    protected $hidden = [
        'password'
    ];
}
