<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;

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
            'name' => 'bail|required|string|max:25|unique:users,name',
            'email' => 'bail|required|string|max:50|email|unique:users,email',
            'password' => 'bail|required|string|min:8',
            'role_id' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (
                        !ctype_digit($value)
                        || strlen($value) > 10
                        || !auth()->user()->role->assignables()->contains('id', $value)
                    ) {
                        $fail('The :attribute is invalid.');
                    }
                },
            ],
        ];
    }

    public function attributes()
    {
        return [
            'role_id' => 'role',
        ];
    }
}
