<?php

namespace App\Exceptions;

use Symfony\Component\HttpFoundation\Response;

class WorkScheduleSettingsIsAlreadyExistingException extends BaseException
{
    public function __construct()
    {
        parent::__construct(
            __('users.work_schedule.exceptions.specialist.work_schedule_settings'),
            Response::HTTP_BAD_REQUEST
        );
    }
}
