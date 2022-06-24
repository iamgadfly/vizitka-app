<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class ClientNotFoundException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.client_not_found'),
            Response::HTTP_NOT_FOUND
        );
    }
}
