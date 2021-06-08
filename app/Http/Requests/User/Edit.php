<?php

namespace App\Http\Requests\User;

use Illuminate\Foundation\Http\FormRequest;
use App\User;

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
        $user = User::with('role')->findOrFail($id);

        $rules = [
            'name' => 'bail|required|string|max:25|unique:users,name,'.$id,
            'email' => 'bail|required|string|max:50|email|unique:users,email,'.$id,
            'role_id' => [
                'nullable',
                function ($attribute, $value, $fail) use ($user) {
                    if (
                        !ctype_digit($value)
                        || strlen($value) > 10
                        || !auth()->user()->role->assignables($user->role)->contains('id', $value)
                    ) {
                        $fail('The :attribute is invalid.');
                    }
                },
            ],
        ];

        if(isset($this->password))
            $rules['password'] = 'bail|string|min:8';

        return $rules;
    }

    public function attributes()
    {
        return [
            'role_id' => 'role',
        ];
    }
}
