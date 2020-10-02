<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudentRequest extends FormRequest
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
            'student_name' => 'required|string|min:2|max:255',
            'record_book'  => 'required|string|min:2|max:255',
            'group_id'     => 'required|integer|min:1',
        ];
    }

    public function attributes()
    {
        return [
            'student_name' => 'ФИО студента',
            'record_book' => '№ Зачетной книжки',
            'group_id' => 'Группа',
        ];
    }
}
