<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class MaintenanceSettingsIsAlreadyExistingException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.maintenance.exceptions.specialist.maintenance_settings'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
