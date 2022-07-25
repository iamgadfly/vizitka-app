<?php

namespace App\Models;

use DateTime;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Specialist
 *
 * @package App\Models
 *
 * @property int $id
 * @property int $user_id
 * @property string $name
 * @property string|null $surname
 * @property int $activity_kind_id
 * @property string|null $vk_account
 * @property string|null $youtube_account
 * @property string|null $tiktok_account
 * @property int|null $avatar_id
 * @property boolean $is_registered
 * @property DateTime $deleted_at
 * @property DateTime $created_at
 * @property DateTime $updated_at
 *
 * Relations
 * @property User $user
 * @property ActivityKind $activity_kind
 * @property Image $avatar
 * @property BusinessCard $card
 * @property MaintenanceSettings $maintenanceSettings
 * @property WorkScheduleSettings $scheduleSettings
 */
class Specialist extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = ['id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function activity_kind(): BelongsTo
    {
        return $this->belongsTo(ActivityKind::class, 'activity_kind_id', 'id');
    }

    public function avatar(): BelongsTo
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'id');
    }

    public function card(): HasOne
    {
        return $this->hasOne(BusinessCard::class, 'specialist_id', 'id');
    }

    public function maintenanceSettings(): HasOne
    {
        return $this->hasOne(MaintenanceSettings::class, 'specialist_id', 'id');
    }

    public function scheduleSettings()
    {
        return $this->hasOne(WorkScheduleSettings::class, 'specialist_id', 'id');
    }
}
