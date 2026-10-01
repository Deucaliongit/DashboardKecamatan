<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SekolahRequest extends FormRequest
{
    /**
     * Admin boleh untuk semua aksi. User Sekolah hanya boleh saat
     * mengedit (update) sekolahnya sendiri — dicek lewat route model binding.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->isAdmin()) {
            return true;
        }

        $sekolah = $this->route('sekolah');

        return $sekolah && $user->sekolah_id === $sekolah->id;
    }

    public function rules(): array
    {
        return [
            'desa_id' => ['required', 'exists:desas,id'],
            'nama_sekolah' => ['required', 'string', 'max:255'],
            'jenjang' => ['required', Rule::in(['SD', 'SMP', 'SMA'])],
            'jumlah_guru' => ['required', 'integer', 'min:0'],
            'jumlah_siswa_l' => ['required', 'integer', 'min:0'],
            'jumlah_siswa_p' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'desa_id.required' => 'Desa wajib dipilih.',
            'nama_sekolah.required' => 'Nama sekolah wajib diisi.',
            'jenjang.required' => 'Jenjang wajib dipilih.',
            'jumlah_guru.required' => 'Jumlah guru wajib diisi.',
            'jumlah_siswa_l.required' => 'Jumlah siswa laki-laki wajib diisi.',
            'jumlah_siswa_p.required' => 'Jumlah siswa perempuan wajib diisi.',
        ];
    }
}
