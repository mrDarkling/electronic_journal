<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UserRequest extends FormRequest
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
            'username' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . intval($this->get('id', 0)),
            'password' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'username' => 'ФИО преподавателя',
            'email' => 'email преподавателя',
            'password' => 'Пароль',
        ];
    }
}
