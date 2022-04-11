<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class VerificationCodeIsntValidException extends BaseAuthException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.exceptions.verification_code_inst_valid'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
