<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class LinkHasExpiredException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.shares.exceptions.linkHasExpired'),
            Response::HTTP_BAD_REQUEST
        );
    }

}
