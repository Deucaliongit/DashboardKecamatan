@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

@if ($isAdmin)
    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Sekolah (kosongkan jika balita / tidak bersekolah)</label>
        <select name="sekolah_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Tidak terkait sekolah (Balita) --</option>
            @foreach ($sekolahs as $s)
                <option value="{{ $s->id }}" @selected(old('sekolah_id', $anak->sekolah_id ?? '') == $s->id)>
                    {{ $s->nama_sekolah }} ({{ $s->desa->nama_desa }})
                </option>
            @endforeach
        </select>
    </div>

    <div class="mb-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Desa (dipakai jika tidak memilih sekolah)</label>
        <select name="desa_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Desa --</option>
            @foreach ($desas as $d)
                <option value="{{ $d->id }}" @selected(old('desa_id', $anak->desa_id ?? '') == $d->id)>{{ $d->nama_desa }}</option>
            @endforeach
        </select>
        @error('desa_id')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    </div>
@else
    <div class="mb-4 text-sm text-gray-600 bg-gray-50 border border-gray-200 rounded p-3">
        Data ini otomatis tercatat di sekolah Anda: <strong>{{ auth()->user()->sekolah->nama_sekolah }}</strong>
    </div>
@endif

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Anak</label>
    <input type="text" name="nama_anak" value="{{ old('nama_anak', $anak->nama_anak ?? '') }}"
           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('nama_anak')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Lahir</label>
    <input type="date" name="tanggal_lahir"
           value="{{ old('tanggal_lahir', isset($anak) ? $anak->tanggal_lahir->format('Y-m-d') : '') }}"
           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('tanggal_lahir')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
    <p class="text-xs text-gray-400 mt-1">Umur dihitung otomatis dari tanggal lahir ini, jadi tidak perlu diinput manual.</p>
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
    <select name="jenis_kelamin" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="L" @selected(old('jenis_kelamin', $anak->jenis_kelamin ?? '') == 'L')>Laki-laki</option>
        <option value="P" @selected(old('jenis_kelamin', $anak->jenis_kelamin ?? '') == 'P')>Perempuan</option>
    </select>
    @error('jenis_kelamin')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Status Stunting</label>
    <select name="status_stunting" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        <option value="Normal" @selected(old('status_stunting', $anak->status_stunting ?? 'Normal') == 'Normal')>Normal</option>
        <option value="Stunting" @selected(old('status_stunting', $anak->status_stunting ?? '') == 'Stunting')>Stunting</option>
    </select>
    @error('status_stunting')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
