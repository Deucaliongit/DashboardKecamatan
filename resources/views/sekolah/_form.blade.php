@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Desa</label>
    @if ($isAdmin)
        <select name="desa_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Desa --</option>
            @foreach ($desas as $d)
                <option value="{{ $d->id }}" @selected(old('desa_id', $sekolah->desa_id ?? '') == $d->id)>{{ $d->nama_desa }}</option>
            @endforeach
        </select>
        @error('desa_id')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    @else
        <input type="text" value="{{ $sekolah->desa->nama_desa }}" disabled
               class="w-full border border-gray-200 bg-gray-100 rounded px-3 py-2 text-sm text-gray-500">
        <input type="hidden" name="desa_id" value="{{ $sekolah->desa_id }}">
    @endif
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Sekolah</label>
    <input type="text" name="nama_sekolah" value="{{ old('nama_sekolah', $sekolah->nama_sekolah ?? '') }}"
           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('nama_sekolah')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Jenjang</label>
    <select name="jenjang" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        @foreach (['SD', 'SMP', 'SMA'] as $j)
            <option value="{{ $j }}" @selected(old('jenjang', $sekolah->jenjang ?? '') == $j)>{{ $j }}</option>
        @endforeach
    </select>
    @error('jenjang')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Guru</label>
    <input type="number" min="0" name="jumlah_guru" value="{{ old('jumlah_guru', $sekolah->jumlah_guru ?? 0) }}"
           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('jumlah_guru')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="grid grid-cols-2 gap-4 mb-4">
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Siswa Laki-laki</label>
        <input type="number" min="0" name="jumlah_siswa_l" id="jumlah_siswa_l" oninput="hitungTotalSiswa()"
               value="{{ old('jumlah_siswa_l', $sekolah->jumlah_siswa_l ?? 0) }}"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('jumlah_siswa_l')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Siswa Perempuan</label>
        <input type="number" min="0" name="jumlah_siswa_p" id="jumlah_siswa_p" oninput="hitungTotalSiswa()"
               value="{{ old('jumlah_siswa_p', $sekolah->jumlah_siswa_p ?? 0) }}"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('jumlah_siswa_p')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Total Siswa (otomatis)</label>
    <input type="text" id="total_siswa" disabled
           value="{{ (int) old('jumlah_siswa_l', $sekolah->jumlah_siswa_l ?? 0) + (int) old('jumlah_siswa_p', $sekolah->jumlah_siswa_p ?? 0) }}"
           class="w-full border border-gray-200 bg-gray-100 rounded px-3 py-2 text-sm text-gray-600 font-semibold">
</div>

<script>
    function hitungTotalSiswa() {
        const l = parseInt(document.getElementById('jumlah_siswa_l').value) || 0;
        const p = parseInt(document.getElementById('jumlah_siswa_p').value) || 0;
        document.getElementById('total_siswa').value = l + p;
    }
</script>
