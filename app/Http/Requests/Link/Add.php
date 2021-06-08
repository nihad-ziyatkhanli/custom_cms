<?php

namespace App\Http\Requests\Link;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Custom\Services\ValidationService as Val;

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
        $locale = $this->input('locale');
        $group = $this->input('group');
        $is_locale = Val::isLocale($locale);
        $is_lg = Val::isLg($group);
        
        $rules = [
            'locale' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) use ($is_locale) {
                    if (!$is_locale) {
                        $fail(__('validation.exists'));
                    }
                },
            ],
            'group' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) use ($is_lg) {
                    if (!$is_lg) {
                        $fail(__('validation.exists'));
                    }
                },
            ],
            'visibility' => function ($attribute, $value, $fail) {
                if (!Val::isVm($value)) {
                    $fail(__('validation.exists'));
                }
            },
            'title' => 'bail|required|string|max:25',
            'code' => [
                'bail',
                'required',
                'string',
                'max:25',
            ],
            'icon' => 'bail|nullable|string|max:100',
            'parent_id' => [
                'bail',
                'nullable',
                'string',
            ],
            'url' => 'bail|nullable|string|max:100',
            'rank' =>'bail|nullable|integer|min:1|max:1000',
        ];

        if ($is_locale) {
            $rules['code'][] = Rule::unique('links', 'code')->where(function ($query) use ($locale) {
                $query->where('locale', '=', $locale);
            });
            if ($is_lg)
                $rules['parent_id'][] = Rule::exists('links', 'id')->where(function ($query) use ($locale, $group) {
                    $query->where('locale', '=', $locale)->where('group', '=', $group);
                });
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'parent_id' => __('parent'),
        ];
    }
}
