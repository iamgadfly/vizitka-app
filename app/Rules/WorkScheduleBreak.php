<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class WorkScheduleBreak implements Rule
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
        foreach ($value as $break) {
            if (array_key_exists('start', $break) && array_key_exists('end', $break)
                && array_key_exists('day', $break)) {
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
        return 'The validation error message.';
    }
}
