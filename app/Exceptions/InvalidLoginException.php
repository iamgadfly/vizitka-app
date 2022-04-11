<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidLoginException extends BaseAuthException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.invalid_login'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
