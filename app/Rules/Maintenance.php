<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use function PHPUnit\Framework\isInstanceOf;

class Maintenance implements Rule
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
        foreach ($value as $item) {
            if (array_key_exists('title', $item) && array_key_exists('duration', $item)) {
                if (is_string($item['title']) && is_int($item['duration'])) {
                    continue;
                }
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
        return 'is not valid';
    }
}
