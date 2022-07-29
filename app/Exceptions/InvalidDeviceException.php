<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class InvalidDeviceException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.invalid_device'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
