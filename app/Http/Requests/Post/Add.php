<?php

namespace App\Http\Requests\Post;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use App\Custom\Helpers\Helper;
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
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug(Helper::str($this->title)),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        return [
            'locale' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    if (!Val::isLocale($value)) {
                        $fail(__('validation.exists'));
                    }
                },
            ],
            'visibility' => [
                'bail',
                'required',
                function ($attribute, $value, $fail) {
                    if (!Val::isVm($value)) {
                        $fail(__('validation.exists'));
                    }
                },
            ],
            'category_id' => 'bail|nullable|integer|exists:categories,id',
            'file_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (
                        !ctype_digit($value)
                        || strlen($value) > 10
                        || auth()->user()->attachments()->where('id', '=', $value)->doesntExist()
                    ) {
                        $fail(__('validation.exists'));
                    }
                },
            ],
            'title' => 'bail|required|string|max:150',
            'slug' => 'bail|required|string|max:150|unique:posts,slug',
            'excerpt' => 'bail|nullable|string|max:500',
            'content' => 'bail|required|string|max:10000',
        ];
    }

    public function attributes()
    {
        return [
            'category_id' => __('category'),
            'file_id' => __('file'),
        ];
    }
}
