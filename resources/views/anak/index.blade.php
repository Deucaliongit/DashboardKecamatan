@extends('layouts.app')

@section('title', 'Data Anak & Stunting')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Anak &amp; Stunting</h1>
    <a href="{{ route('anak.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Data Anak
    </a>
</div>

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded p-3">{{ session('success') }}</div>
@endif

<form method="GET" class="bg-white rounded-lg shadow p-4 mb-6 flex flex-wrap gap-3 items-end">
    <div>
        <label class="block text-xs text-gray-500 mb-1">Cari Nama</label>
        <input type="text" name="cari" value="{{ $cari }}" placeholder="Nama anak..."
               class="border border-gray-300 rounded px-3 py-1.5 text-sm">
    </div>

    @if ($isAdmin)
        <div>
            <label class="block text-xs text-gray-500 mb-1">Desa</label>
            <select name="desa_id" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                <option value="">Semua Desa</option>
                @foreach ($desas as $d)
                    <option value="{{ $d->id }}" @selected($desaId == $d->id)>{{ $d->nama_desa }}</option>
                @endforeach
            </select>
        </div>
    @endif

    <div>
        <label class="block text-xs text-gray-500 mb-1">Kelompok Umur</label>
        <select name="kelompok_umur" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
            <option value="">Semua Umur</option>
            <option value="balita" @selected($kelompok == 'balita')>Balita (0-5 th)</option>
            <option value="anak" @selected($kelompok == 'anak')>Anak (6-12 th)</option>
            <option value="remaja" @selected($kelompok == 'remaja')>Remaja (13-18 th)</option>
            <option value="dewasa" @selected($kelompok == 'dewasa')>Dewasa (&gt;18 th)</option>
        </select>
    </div>

    <div>
        <label class="block text-xs text-gray-500 mb-1">Status</label>
        <select name="status_stunting" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
            <option value="">Semua Status</option>
            <option value="Normal" @selected($statusStunting == 'Normal')>Normal</option>
            <option value="Stunting" @selected($statusStunting == 'Stunting')>Stunting</option>
        </select>
    </div>

    <button type="submit" class="bg-gray-800 text-white text-sm px-4 py-1.5 rounded hover:bg-gray-900">Filter</button>
    <a href="{{ route('anak.index') }}" class="text-sm text-gray-500 hover:underline px-2 py-1.5">Reset</a>
</form>

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 border-b">
            <tr>
                <th class="py-3 px-4">Nama Anak</th>
                <th class="py-3 px-4">Umur</th>
                <th class="py-3 px-4">Jenis Kelamin</th>
                <th class="py-3 px-4">Sekolah</th>
                @if ($isAdmin)
                    <th class="py-3 px-4">Faskes</th>
                    <th class="py-3 px-4">Desa</th>
                @endif
                <th class="py-3 px-4">Status</th>
                <th class="py-3 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($anaks as $anak)
                <tr class="border-b last:border-0">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $anak->nama_anak }}</td>
                    <td class="py-3 px-4">{{ $anak->umur }} th</td>
                    <td class="py-3 px-4">{{ $anak->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</td>
                    <td class="py-3 px-4">{{ $anak->sekolah->nama_sekolah ?? '-' }}</td>
                    @if ($isAdmin)
                        <td class="py-3 px-4">{{ $anak->faskes->nama_faskes ?? '-' }}</td>
                        <td class="py-3 px-4">{{ $anak->desa->nama_desa }}</td>
                    @endif
                    <td class="py-3 px-4">
                        @if ($anak->status_stunting === 'Stunting')
                            <span class="bg-red-100 text-red-700 text-xs px-2 py-1 rounded-full">Stunting</span>
                        @else
                            <span class="bg-green-100 text-green-700 text-xs px-2 py-1 rounded-full">Normal</span>
                        @endif
                    </td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="{{ route('anak.edit', $anak) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('anak.destroy', $anak) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin hapus data anak ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="{{ $isAdmin ? 8 : 6 }}" class="py-6 px-4 text-center text-gray-400">
                        Tidak ada data yang cocok.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $anaks->links() }}
</div>
@endsection
