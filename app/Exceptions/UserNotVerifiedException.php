<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserNotVerifiedException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.user_not_verified'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
