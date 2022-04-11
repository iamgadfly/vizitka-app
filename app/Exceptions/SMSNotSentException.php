<?php

namespace App\Exceptions;

use Exception;
use Symfony\Component\HttpFoundation\Response;

class SMSNotSentException extends BaseAuthException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.sms_not_sent'),
            Response::HTTP_INTERNAL_SERVER_ERROR
        );
    }
}
