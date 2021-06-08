<?php

namespace App\Http\Requests\Category;

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
            'title' => 'bail|required|string|max:25|unique:categories,title,'.$id,
            'code' => 'bail|required|string|max:25|unique:categories,code,'.$id,
        ];
    }
}
