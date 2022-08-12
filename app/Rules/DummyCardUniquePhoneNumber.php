<?php

namespace App\Rules;

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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $client = Client::where('user_id', Auth::id())->first();
        DummyBusinessCard::where([
            'phone_number' => $this->phone,
            'client_id' => $client->id
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
