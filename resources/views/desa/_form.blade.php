<div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Desa</label>
    <input type="text" name="nama_desa" value="{{ old('nama_desa', $desa->nama_desa ?? '') }}"
           class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
    @error('nama_desa')
        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
    @enderror
</div>
