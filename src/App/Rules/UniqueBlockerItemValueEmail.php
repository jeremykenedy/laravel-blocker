<?php

namespace jeremykenedy\LaravelBlocker\App\Rules;

use Illuminate\Contracts\Validation\Rule;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;

class UniqueBlockerItemValueEmail implements Rule
{
    private $typeId;

    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct($typeId)
    {
        $this->typeId = $typeId;
    }

    /**
     * Determine if the validation rule passes.
     *
     * @param string $attribute
     * @param mixed  $value
     *
     * @return bool
     */
    public function passes($attribute, $value)
    {
        if ($this->typeId) {
            $type = BlockedType::find($this->typeId);

            if ($type->slug == 'email' || $type->slug == 'user') {
                $check = $this->checkEmail($value);

                if ($check) {
                    return $value;
                }

                return false;
            }
        }

        return true;
    }

    /**
     * Check if value is proper formed email.
     *
     * @param string $email The email
     *
     * @return bool
     */
    public function checkEmail($email)
    {
        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');

        return $find1 !== false && $find2 !== false && $find2 > $find1 ? true : false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('laravelblocker::laravelblocker.validation.email');
    }
}
