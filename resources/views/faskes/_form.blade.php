@php
    $isAdmin = auth()->user()->isAdmin();
@endphp

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Desa</label>
    @if ($isAdmin)
        <select name="desa_id" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">-- Pilih Desa --</option>
            @foreach ($desas as $d)
                <option value="{{ $d->id }}" @selected(old('desa_id', $faskes->desa_id ?? '') == $d->id)>{{ $d->nama_desa }}</option>
            @endforeach
        </select>
        @error('desa_id')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    @else
        <input type="text" value="{{ $faskes->desa->nama_desa }}" disabled
               class="w-full border border-gray-200 bg-gray-100 rounded px-3 py-2 text-sm text-gray-500">
        <input type="hidden" name="desa_id" value="{{ $faskes->desa_id }}">
    @endif
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Fasilitas Kesehatan</label>
    @if ($isAdmin)
        <input type="text" name="nama_faskes" value="{{ old('nama_faskes', $faskes->nama_faskes ?? '') }}"
               placeholder="Contoh: Puskesmas Sukamaju"
               class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
        @error('nama_faskes')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    @else
        <input type="text" value="{{ $faskes->nama_faskes }}" disabled
               class="w-full border border-gray-200 bg-gray-100 rounded px-3 py-2 text-sm text-gray-500">
        <input type="hidden" name="nama_faskes" value="{{ $faskes->nama_faskes }}">
    @endif
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis</label>
    @if ($isAdmin)
        <select name="jenis" class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            @foreach (['Puskesmas', 'Pustu', 'Posyandu'] as $j)
                <option value="{{ $j }}" @selected(old('jenis', $faskes->jenis ?? '') == $j)>{{ $j }}</option>
            @endforeach
        </select>
        @error('jenis')
            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
        @enderror
    @else
        <input type="text" value="{{ $faskes->jenis }}" disabled
               class="w-full border border-gray-200 bg-gray-100 rounded px-3 py-2 text-sm text-gray-500">
        <input type="hidden" name="jenis" value="{{ $faskes->jenis }}">
    @endif
</div>

<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tenaga Medis</label>
    <input type="number" min="0" name="jumlah_tenaga_medis"
           value="{{ old('jumlah_tenaga_medis', $faskes->jumlah_tenaga_medis ?? 0) }}"
           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('jumlah_tenaga_medis')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
