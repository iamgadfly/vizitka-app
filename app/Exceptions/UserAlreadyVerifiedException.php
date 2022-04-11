<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserAlreadyVerifiedException extends BaseAuthException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.verified'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
