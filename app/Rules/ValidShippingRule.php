<?php

namespace App\Rules;

use App\Models\Address;
use Illuminate\Contracts\Validation\Rule;

class ValidShippingRule implements Rule
{
	protected $addressId;

	/**
	 * Create a new rule instance.
	 *
	 * @param $addressId
	 */
    public function __construct($addressId)
    {
        $this->addressId = $addressId;
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
        if (!$address = $this->getAddress()) return false;

        return $address->country->shippingMethods->contains('id', $value);
    }

    /**˚
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'Invalid shipping method.';
    }

	protected function getAddress()
	{
		return Address::find($this->addressId);
    }
}
