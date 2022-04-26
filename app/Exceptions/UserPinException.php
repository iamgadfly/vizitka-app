<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class UserPinException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.specialist.pin_exception'),
            Response::HTTP_UNAUTHORIZED
        );
    }
}
