<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

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
        return [
            //
        ];
    }

}
