<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class RecordNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.other.exceptions.record_not_found'),
            Response::HTTP_NOT_FOUND
        );
    }
}
