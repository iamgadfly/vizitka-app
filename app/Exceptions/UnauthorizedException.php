<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UnauthorizedException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.unauthorized'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
