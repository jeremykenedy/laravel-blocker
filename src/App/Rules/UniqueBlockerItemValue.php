<?php

namespace jeremykenedy\LaravelBlocker\App\Rules;

use Illuminate\Contracts\Validation\Rule;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;

class UniqueBlockerItemValue implements Rule
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
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        $itemSearched = BlockedItem::where('value', '=', $value)
            ->withTrashed()
            ->first();

            if ($itemSearched === null) {
                return $value;
            }

        return false;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return trans('laravelblocker::laravelblocker.validation.blockedExists', ['attribute' => ':attribute']);
    }

}
