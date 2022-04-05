<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specialist extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function activity_kind()
    {
        return $this->belongsTo(ActivityKind::class, 'activity_kind_id', 'id');
    }

    public function avatar()
    {
        return $this->belongsTo(Image::class, 'avatar_id', 'id');
    }

    public function card()
    {
        return $this->hasOne(BusinessCard::class, 'specialist_id', 'id');
    }
}
