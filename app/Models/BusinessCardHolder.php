<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BusinessCardHolder extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function client(): HasOne
    {
        return $this->hasOne(Client::class, 'client_id', 'id');
    }

    public function card(): HasOne
    {
        return $this->hasOne(BusinessCard::class, 'business_card_id', 'id');
    }
}
