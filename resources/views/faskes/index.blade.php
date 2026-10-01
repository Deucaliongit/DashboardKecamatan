@extends('layouts.app')

@section('title', 'Fasilitas Kesehatan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Fasilitas Kesehatan</h1>
    <div class="flex items-center gap-3">
        <form method="GET">
            <select name="desa_id" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                <option value="">Semua Desa</option>
                @foreach ($desas as $d)
                    <option value="{{ $d->id }}" @selected($desaId == $d->id)>{{ $d->nama_desa }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('faskes.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Faskes
        </a>
    </div>
</div>

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded p-3">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 border-b">
            <tr>
                <th class="py-3 px-4">Nama Faskes</th>
                <th class="py-3 px-4">Jenis</th>
                <th class="py-3 px-4">Desa</th>
                <th class="py-3 px-4">Jml Tenaga Medis</th>
                <th class="py-3 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($faskes as $f)
                <tr class="border-b last:border-0">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $f->nama_faskes }}</td>
                    <td class="py-3 px-4">
                        <span class="text-xs px-2 py-1 rounded-full
                            @class([
                                'bg-blue-100 text-blue-700' => $f->jenis === 'Puskesmas',
                                'bg-amber-100 text-amber-700' => $f->jenis === 'Pustu',
                                'bg-emerald-100 text-emerald-700' => $f->jenis === 'Posyandu',
                            ])">
                            {{ $f->jenis }}
                        </span>
                    </td>
                    <td class="py-3 px-4">{{ $f->desa->nama_desa }}</td>
                    <td class="py-3 px-4">{{ $f->jumlah_tenaga_medis }}</td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="{{ route('faskes.edit', $f) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('faskes.destroy', $f) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin hapus data fasilitas kesehatan ini?');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-6 px-4 text-center text-gray-400">Belum ada data fasilitas kesehatan.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $faskes->appends(['desa_id' => $desaId])->links() }}
</div>
@endsection
