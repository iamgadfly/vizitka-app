<?php

namespace App\Helpers;

use App\Exceptions\ClientNotFoundException;
use App\Exceptions\SpecialistNotFoundException;

class AuthHelper
{
    /**
     * @throws SpecialistNotFoundException
     */
    public static function getSpecialistIdFromAuth(): ?int
    {
        return auth()->user()->specialist->id ?? throw new SpecialistNotFoundException;
    }

    public static function getClientIdFromAuth(): ?int
    {
        return auth()->user()->client->id ?? throw new ClientNotFoundException;
    }
}
