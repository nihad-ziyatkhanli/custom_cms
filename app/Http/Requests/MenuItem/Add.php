<?php

namespace App\Http\Requests\MenuItem;

use Illuminate\Foundation\Http\FormRequest;
use App\MenuItem;

class Add extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
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
            'title' => 'bail|required|string|max:25|unique:menu_items,title',
            'code' => 'bail|required|string|max:25|unique:menu_items,code',
            'icon' => 'bail|nullable|string|max:25',
            'parent_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (
                        !ctype_digit($value)
                        || strlen($value) > 10
                        || MenuItem::validParents()->where('id', '=', $value)->doesntExist()
                    ) {
                        $fail('The :attribute is invalid.');
                    }
                },
            ],
            'url' => 'bail|nullable|string|max:100',
            'rank' =>'bail|nullable|integer|min:1|max:1000',
        ];
    }

    public function attributes()
    {
        return [
            'parent_id' => 'parent',
        ];
    }
}
