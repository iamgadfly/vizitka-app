<?php

namespace App\Models;

use App\Exceptions\SpecialistNotFoundException;
use App\Helpers\AuthHelper;
use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Client
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $surname
 * @property int|null $avatar_id
 * @property DateTime|null $deleted_at
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 *
 * @property User $user
 * @property Image $avatar
 */
class Client extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'id');
    }

    /**
     * @return ContactData|null
     * @throws SpecialistNotFoundException
     */
    public function contactData(): ?ContactData
    {
        return ContactData::where([
            'client_id' => $this->id,
            'specialist_id' => AuthHelper::getSpecialistIdFromAuth()
        ])->first() ?? null;
    }
}
