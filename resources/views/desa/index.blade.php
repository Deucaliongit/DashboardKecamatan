@extends('layouts.app')

@section('title', 'Data Desa')

@section('content')
<div class="flex items-center justify-between mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Desa</h1>
    <a href="{{ route('desa.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
        + Tambah Desa
    </a>
</div>

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded p-3">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow overflow-x-auto">
    <table class="w-full text-sm text-left">
        <thead class="bg-gray-50 text-gray-500 border-b">
            <tr>
                <th class="py-3 px-4">Nama Desa</th>
                <th class="py-3 px-4">Jml Sekolah</th>
                <th class="py-3 px-4">Jml Anak Terdata</th>
                <th class="py-3 px-4">Jml Faskes</th>
                <th class="py-3 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($desas as $desa)
                <tr class="border-b last:border-0">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $desa->nama_desa }}</td>
                    <td class="py-3 px-4">{{ $desa->sekolahs_count }}</td>
                    <td class="py-3 px-4">{{ $desa->anaks_count }}</td>
                    <td class="py-3 px-4">{{ $desa->faskes_count }}</td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="{{ route('desa.edit', $desa) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('desa.destroy', $desa) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin hapus desa ini? Data sekolah/anak/faskes terkait juga akan ikut terhapus.');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="py-6 px-4 text-center text-gray-400">Belum ada data desa.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $desas->links() }}
</div>
@endsection
