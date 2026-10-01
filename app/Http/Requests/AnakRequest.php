<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnakRequest extends FormRequest
{
    /**
     * Admin boleh untuk semua aksi. Admin Puskesmas hanya boleh untuk anak
     * yang terhubung ke fasilitas kesehatannya sendiri.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->isAdmin()) {
            return true;
        }

        if (! $user->isPuskesmas()) {
            return false;
        }

        $anak = $this->route('anak'); // null saat create

        if ($anak) {
            return $user->faskes_id === $anak->faskes_id;
        }

        return true; // saat create, faskes_id dipaksa ke faskes sendiri di controller
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
            // desa_id wajib HANYA jika Sekolah maupun Puskesmas tidak dipilih
            // (kalau salah satunya dipilih, desa_id otomatis ikut desa dari situ)
            $rules['desa_id'] = ['required_without_all:sekolah_id,faskes_id', 'nullable', 'exists:desas,id'];
            $rules['sekolah_id'] = ['nullable', 'exists:sekolahs,id'];
            $rules['faskes_id'] = ['nullable', 'exists:faskes,id'];
        }

        return $rules;
    }

    public function messages(): array
    {
        return [
            'nama_anak.required' => 'Nama anak wajib diisi.',
            'tanggal_lahir.required' => 'Tanggal lahir wajib diisi.',
            'tanggal_lahir.before_or_equal' => 'Tanggal lahir tidak boleh di masa depan.',
            'desa_id.required_without_all' => 'Pilih Desa, atau pilih Sekolah/Puskesmas terlebih dahulu.',
            'jenis_kelamin.required' => 'Jenis kelamin wajib dipilih.',
            'status_stunting.required' => 'Status stunting wajib dipilih.',
        ];
    }
}
