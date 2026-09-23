<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use jeremykenedy\LaravelBlocker\App\Models\BlockedItem;
use jeremykenedy\LaravelBlocker\App\Models\BlockedType;
use jeremykenedy\LaravelBlocker\App\Rules\UniqueBlockerItemValueEmail;

class StoreBlockerRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        if (config('laravelblocker.rolesEnabled')) {
            return config('laravelblocker.rolesMiddlware');
        }

        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $item = new BlockedItem();
        $type = new BlockedType();
        $userModel = config('laravelblocker.defaultUserModel');
        $user = new $userModel();
        $unique = Rule::unique($item->getConnectionName().'.'.$item->getTable(), 'value');
        if ($this->route('blocker')) {
            $unique->ignore($this->route('blocker'));
        }

        return [
            'typeId' => ['required', 'integer', Rule::exists($type->getConnectionName().'.'.$type->getTable(), 'id')->whereNull('deleted_at')],
            'value'  => ['required', 'max:255', 'string', $unique, new UniqueBlockerItemValueEmail($this->input('typeId'))],
            'note'   => 'nullable|max:500|string',
            'userId' => ['nullable', 'integer', Rule::exists($user->getConnection()->getName().'.'.$user->getTable(), $user->getKeyName())],
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'typeId.required'   => trans('laravelblocker::laravelblocker.validation.blockedTypeRequired'),
            'value.required'    => trans('laravelblocker::laravelblocker.validation.blockedValueRequired'),
        ];
    }

    /**
     * Return the fields and values for a Blocked Item.
     *
     * @return array
     */
    public function blockedFillData()
    {
        $userId = null;
        if ($this->userId) {
            $userId = $this->userId;
        }

        return [
            'typeId'    => $this->typeId,
            'value'     => $this->value,
            'note'      => $this->note,
            'userId'    => $userId,
        ];
    }
}
