<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class TooManyLoginAttemptsException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.too_many_login'),
            Response::HTTP_TOO_MANY_REQUESTS
        );
    }
}
