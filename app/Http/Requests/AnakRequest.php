<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnakRequest extends FormRequest
{
    /**
     * Admin boleh untuk semua aksi. User Sekolah hanya boleh untuk anak
     * yang terhubung ke sekolahnya sendiri.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->isAdmin()) {
            return true;
        }

        $anak = $this->route('anak'); // null saat create

        if ($anak) {
            return $user->sekolah_id === $anak->sekolah_id;
        }

        return true; // saat create, sekolah_id akan dipaksa ke sekolah sendiri di controller
    }

    public function rules(): array
    {
        $rules = [
            'nama_anak' => ['required', 'string', 'max:255'],
            'tanggal_lahir' => ['required', 'date', 'before_or_equal:today'],
            'jenis_kelamin' => ['required', Rule::in(['L', 'P'])],
            'status_stunting' => ['required', Rule::in(['Normal', 'Stunting'])],
        ];

        if ($this->user()->isAdmin()) {
            $rules['desa_id'] = ['required', 'exists:desas,id'];
            $rules['sekolah_id'] = ['nullable', 'exists:sekolahs,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama_anak.required' => 'Nama anak wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'desa_id.required' => 'Desa wajib dipilih.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_stunting.required' => 'Status stunting wajib dipilih.',
        ];
    }
}
