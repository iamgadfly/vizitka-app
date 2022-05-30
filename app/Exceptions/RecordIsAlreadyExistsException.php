<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class RecordIsAlreadyExistsException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.other.exceptions.record_is_already_exists'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
