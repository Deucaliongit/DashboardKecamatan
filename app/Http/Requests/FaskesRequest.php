<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class FaskesRequest extends FormRequest
{
    /**
     * Admin boleh untuk semua aksi. Admin Puskesmas hanya boleh saat
     * mengedit (update) fasilitas kesehatannya sendiri.
     */
    public function authorize(): bool
    {
        $user = $this->user();

        if ($user->isAdmin()) {
            return true;
        }

        $faskes = $this->route('faskes'); // null saat create

        return $faskes && $user->isPuskesmas() && $user->faskes_id === $faskes->id;
    }

    public function rules(): array
    {
        return [
            'desa_id' => ['required', 'exists:desas,id'],
            'nama_faskes' => ['required', 'string', 'max:255'],
            'jenis' => ['required', Rule::in(['Puskesmas', 'Pustu', 'Posyandu'])],
            'jumlah_tenaga_medis' => ['required', 'integer', 'min:0'],
        ];
    }

    public function messages(): array
    {
        return [
            'desa_id.required' => 'Desa wajib dipilih.',
            'nama_faskes.required' => 'Nama fasilitas kesehatan wajib diisi.',
            'jenis.required' => 'Jenis fasilitas wajib dipilih.',
            'jumlah_tenaga_medis.required' => 'Jumlah tenaga medis wajib diisi.',
        ];
    }
}
