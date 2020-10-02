<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StudyWeekRequest extends FormRequest
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
        if ($this->isMethod('PUT')) {
            return [
                'is_even' => 'required|boolean',
                'created_at' => 'string',
            ];
        }

        return [
            'is_even' => 'required|boolean',
            'created_at' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'is_even' => 'Четная/нечетная неделя',
            'created_at' => 'Начало недели',
        ];
    }
}
