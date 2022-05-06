<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TimeIsNotValidException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.other.rules.time_is_not_valid'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
