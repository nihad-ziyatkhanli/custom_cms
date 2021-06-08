<?php

namespace App\Http\Requests\File;

use Illuminate\Foundation\Http\FormRequest;

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

        return [
            'title' => 'bail|nullable|string|max:25',
            'caption' => 'bail|nullable|string|max:100',
            'description' => 'bail|nullable|string|max:250',
        ];
    }
}
