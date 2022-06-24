<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class SpecialistNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.specialist_not_found'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
