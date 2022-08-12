<?php

namespace App\Rules;

use App\Exceptions\ClientNotFoundException;
use App\Helpers\AuthHelper;
use App\Models\Client;
use App\Models\DummyBusinessCard;
use App\Services\DummyBusinessCardService;
use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Facades\Auth;

class DummyCardUniquePhoneNumber implements Rule
{
    /**
     * @var string
     */
    private string $phone;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct(string $phone)
    {
        $this->phone = $phone;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed $value
     * @return bool
     * @throws ClientNotFoundException
     */
    public function passes($attribute, $value)
    {
        $clientId = AuthHelper::getClientIdFromAuth();
        return DummyBusinessCard::where([
            'phone_number' => $this->phone,
            'client_id' => $clientId
        ])->first() === null;
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
