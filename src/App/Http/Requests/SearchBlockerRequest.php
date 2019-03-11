<?php

namespace jeremykenedy\LaravelBlocker\App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SearchBlockerRequest extends FormRequest
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
            'blocked_search_box' => 'required|string|max:255',
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
            'blocked_search_box.required' => trans('laravelblocker::laravelblocker.search.required'),
            'blocked_search_box.string'   => trans('laravelblocker::laravelblocker.search.string'),
            'blocked_search_box.max'      => trans('laravelblocker::laravelblocker.search.max'),
        ];
    }
}
