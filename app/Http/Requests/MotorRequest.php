<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class MotorRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'jenis_motor_id' => 'required|exists:jenis_motors,id',
            'nomor_rangka'   => 'required|string|max:255',
            'nomor_mesin'    => 'required|string|max:255',
            'plat'           => 'required|string|max:10',
        ];
    }
}
