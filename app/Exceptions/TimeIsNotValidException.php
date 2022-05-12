<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class TimeIsNotValidException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.specialist_not_created'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
