<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AdminAttendanceCorrectionRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules(): array
    {
        return [
            'clock_in_time'          => ['nullable', 'date_format:H:i'],
            'clock_out_time'         => ['nullable', 'date_format:H:i', 'after:clock_in_time'],
            'breaks'                 => ['nullable', 'array'],
            'breaks.*.start_time'    => ['nullable', 'date_format:H:i'],
            'breaks.*.end_time'      => ['nullable', 'date_format:H:i', 'after:breaks.*.start_time'],
            'remarks'                => ['nullable', 'string', 'max:255'],
        ];
    }
    
    public function messages(): array
    {
        return [
            'clock_out_time.after'       => '退勤時刻は出勤時刻より後にしてください。',
            'breaks.*.end_time.after'    => '休憩終了時刻は開始時刻より後にしてください。',
            'clock_in_time.date_format'  => '出勤時刻はHH:MM形式で入力してください。',
            'clock_out_time.date_format' => '退勤時刻はHH:MM形式で入力してください。',
        ];
    }
}
