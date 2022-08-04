<?php

namespace App\Models;

use DateTime;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

/**
 * Class User
 *
 * @package App\Models
 *
 * @property int $id
 * @property DateTime|null $deleted_at
 * @property string $phone_number
 * @property string|null $pin
 * @property string|null $verification_code
 * @property DateTime|null $phone_number_verified_at
 * @property boolean $is_verified
 * @property string|null $remember_token
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property Specialist $specialist
 * @property Client $client
 */
class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'pin',
        'phone_number',
        'verification_code'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'remember_token',
        'pin'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'phone_number_verified_at' => 'datetime',
    ];

    public function specialist(): HasOne
    {
        return $this->hasOne(Specialist::class, 'user_id', 'id');
    }

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'user_id', 'id');
    }

    public function isSpecialist(): bool
    {
        return !is_null($this->specialist);
    }

    public function isClient(): bool
    {
        return !is_null($this->client);
    }
}
