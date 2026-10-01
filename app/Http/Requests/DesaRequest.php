<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DesaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    public function rules(): array
    {
        return [
            'nama_desa' => [
                'required',
                'string',
                'max:255',
                Rule::unique('desas', 'nama_desa')->ignore($this->route('desa')),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'nama_desa.required' => 'Nama desa wajib diisi.',
            'nama_desa.unique' => 'Nama desa sudah terdaftar.',
        ];
    }
}
