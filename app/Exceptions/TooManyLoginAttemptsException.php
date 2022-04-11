<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TooManyLoginAttemptsException extends BaseAuthException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.too_many_login'),
            Response::HTTP_TOO_MANY_REQUESTS
        );
    }
}
