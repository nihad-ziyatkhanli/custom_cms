<?php

namespace App\Http\Requests\Role;

use Illuminate\Foundation\Http\FormRequest;
use App\Role;

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
            'title' => 'bail|required|string|max:25|unique:roles,title,'.$id,
            'code' => 'bail|required|string|max:25|unique:roles,code,'.$id,
            'rank' => [
                'bail',
                'required',
                'integer',
                'min:1',
                'max:9999',
                function ($attribute, $value, $fail) {
                    if ($value < auth()->user()->role->rank) {
                        $fail('The :attribute must be higher than or equal to your own.');
                    }
                },
            ],
            'status' => 'boolean',
        ];
    }
}
