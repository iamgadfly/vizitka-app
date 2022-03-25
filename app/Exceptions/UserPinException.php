<?php

namespace App\Exceptions;

use Exception;
use JetBrains\PhpStorm\Internal\LanguageLevelTypeAware;
use Symfony\Component\HttpFoundation\Response;

class UserPinException extends BaseAuthException
{
    public function __construct()
    {
        parent::__construct(
            __('users.auth.specialist.pin_exception'),
            Response::HTTP_FORBIDDEN
        );
    }
}
