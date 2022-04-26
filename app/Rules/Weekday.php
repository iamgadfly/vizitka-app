<?php

namespace App\Rules;

use App\Helpers\WeekdayHelper;
use Illuminate\Contracts\Validation\Rule;

class Weekday implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        foreach ($value as $day) {
            if (in_array($day, WeekdayHelper::getAll())) {
                continue;
            }
            return false;
        }
        return true;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return __('users.work_schedule.rules.specialist.weekday');
    }
}
