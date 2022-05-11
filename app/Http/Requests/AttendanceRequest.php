<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AttendanceRequest extends FormRequest
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
            'date' => 'required|string|date_format:d-m-Y',
            'time_start_morning' => 'date_format:H:i',
            'time_end_morning' => 'date_format:H:i',
            'time_start_afternoon' => 'date_format:H:i',
            'time_end_afternoon' => 'date_format:H:i',
            'signature' => 'required|string',
        ];
    }
}
