<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.user_not_found'),
            Response::HTTP_NOT_FOUND
        );
    }
}
