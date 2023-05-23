<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreAppointmentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'patient_CIN' => 'required|string|exists:patients,CIN',
            'start_time' => 'required|date_format:Y-m-d H:i:s|after_or_equal:now',
            'end_time' => 'required|date_format:Y-m-d H:i:s|after:start_time',
            'reason' => 'required|string|max:255',
        ];
    }
}
