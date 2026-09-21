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
        if (!is_scalar($this->typeId) && $this->typeId !== null) {
            return false;
        }
        if (!$this->typeId) {
            return true;
        }
        $type = BlockedType::find($this->typeId);
        if (!$type) {
            return false;
        }

        return !in_array($type->getAttribute('slug'), ['email', 'user']) || $this->checkEmail($value);
    }

    /**
     * Check if value is proper formed email.
     *
     * @param mixed $email The email
     *
     * @return bool
     */
    public function checkEmail($email)
    {
        if (!is_string($email)) {
            return false;
        }

        $find1 = strpos($email, '@');
        $find2 = strpos($email, '.');

        return $find1 !== false && $find2 !== false && $find2 > $find1;
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
