<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use App\Custom\Helpers\Helper;
use App\MenuItem;
use App\Permission;

class Attach extends FormRequest
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
        $attachable_ids = MenuItem::attachableIds();
        $permission_ids = Permission::browse()->pluck('id')->all();

        return [
            'permissions' => [
                'bail',
                'sometimes',
                'required',
                'array',
                'max:99',
                function ($attribute, $value, $fail) use ($attachable_ids) {
                    if (array_diff(array_keys($value), $attachable_ids)) {
                        $fail('Invalid request.');
                    }
                },
            ],
            'permissions.*' => [
                'bail',
                'required',
                'array',
                'max:99',
                function ($attribute, $value, $fail) use ($permission_ids) {
                    if (
                        !Helper::isOneDimensionalArray($value)
                        || count($value) !== count(array_unique($value))
                        || array_diff($value, $permission_ids)
                    ) {
                        $fail('Invalid request.');
                    }
                },
            ],
        ];
    }
}
