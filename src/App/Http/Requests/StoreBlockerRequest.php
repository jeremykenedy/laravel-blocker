<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Request;
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
        $id = $this->route('blocker');

        return [
            'typeId' => 'required|max:255|integer',
            'value'  => ['required', 'max:255', 'string', 'unique:laravel_blocker,value,'.$id.',id', new UniqueBlockerItemValueEmail(Request::input('typeId'))],
            'note'   => 'nullable|max:500|string',
            'userId' => 'nullable|max:255|integer',
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
