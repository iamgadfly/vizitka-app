<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessCardHolder extends Model
{
    use HasFactory;

    public function client(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(Client::class, 'client_id', 'id');
    }

    public function card(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(BusinessCard::class, 'business_card_id', 'id');
    }
}
