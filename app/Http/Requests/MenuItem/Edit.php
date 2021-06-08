<?php

namespace App\Http\Requests\MenuItem;

use Illuminate\Foundation\Http\FormRequest;
use App\MenuItem;
use Illuminate\Validation\Rule;

class Edit extends FormRequest
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
        /* Me: $id is assured to be numeric. */
        $id = $this->route('id');
        $item = MenuItem::with('children', 'rmps')->findOrFail($id);
        
        return [
            'title' => 'bail|required|max:25|unique:menu_items,title,'.$id,
            'code' => 'bail|required|max:25|unique:menu_items,code,'.$id,
            'icon' => 'bail|nullable|string|max:25',
            'parent_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($item) {
                    if (
                        $item->children->isNotEmpty()
                        || !ctype_digit($value)
                        || strlen($value) > 10
                        || MenuItem::validParents($item)->where('id', '=', $value)->doesntExist()
                    ) {
                        $fail('The :attribute is invalid.');
                    }
                },
            ],
            'url' => [
                'bail',
                'nullable',
                'string',
                'max:100',
                Rule::requiredIf(function () use ($item) {
                    return $item->rmps->isNotEmpty();
                }),
                function ($attribute, $value, $fail) use ($item) {
                    if ($item->children->isNotEmpty()) {
                        $fail('The :attribute can be set, only if the menu item has no children.');
                    }
                },
            ],
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
