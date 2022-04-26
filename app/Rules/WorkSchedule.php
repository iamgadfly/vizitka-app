<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WorkSchedule implements Rule
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
        foreach ($value as $schedule) {
            if (array_key_exists('start', $schedule) && array_key_exists('end', $schedule)
                && array_key_exists('is_break', $schedule)) {
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
        return __('users.other.rules.array_is_not_valid');
    }
}
