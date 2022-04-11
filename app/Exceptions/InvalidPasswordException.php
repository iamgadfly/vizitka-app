<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class InvalidPasswordException extends BaseAuthException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.invalid_password'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
