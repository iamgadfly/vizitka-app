<?php

namespace App\Rules;

use App\Models\DummyBusinessCard;
use App\Services\DummyBusinessCardService;
use Illuminate\Contracts\Validation\Rule;

class DummyCardUniquePhoneNumber implements Rule
{
    /**
     * @var string
     */
    private string $phone;

    /**
     * @var int
     */
    private int $clientId;
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $phone, int $clientId)
    {
        $this->phone = $phone;
        $this->clientId = $clientId;
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
        DummyBusinessCard::where([
            'phone_number' => $this->phone,
            'client_id' => $this->clientId
        ])->first() !== null;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Номер телефона занят';
    }
}
