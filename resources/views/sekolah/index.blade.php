@extends('layouts.app')

@section('title', 'Data Sekolah')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Data Sekolah</h1>
    <div class="flex items-center gap-3">
        <form method="GET">
            <select name="desa_id" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
                <option value="">Semua Desa</option>
                @foreach ($desas as $d)
                    <option value="{{ $d->id }}" @selected($desaId == $d->id)>{{ $d->nama_desa }}</option>
                @endforeach
            </select>
        </form>
        <a href="{{ route('sekolah.create') }}" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
            + Tambah Sekolah
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
                <th class="py-3 px-4">Nama Sekolah</th>
                <th class="py-3 px-4">Desa</th>
                <th class="py-3 px-4">Jenjang</th>
                <th class="py-3 px-4">Jml Guru</th>
                <th class="py-3 px-4">Siswa L</th>
                <th class="py-3 px-4">Siswa P</th>
                <th class="py-3 px-4">Total Siswa</th>
                <th class="py-3 px-4 text-right">Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($sekolahs as $s)
                <tr class="border-b last:border-0">
                    <td class="py-3 px-4 font-medium text-gray-800">{{ $s->nama_sekolah }}</td>
                    <td class="py-3 px-4">{{ $s->desa->nama_desa }}</td>
                    <td class="py-3 px-4">{{ $s->jenjang }}</td>
                    <td class="py-3 px-4">{{ $s->jumlah_guru }}</td>
                    <td class="py-3 px-4">{{ $s->jumlah_siswa_l }}</td>
                    <td class="py-3 px-4">{{ $s->jumlah_siswa_p }}</td>
                    <td class="py-3 px-4 font-semibold">{{ $s->jumlah_siswa }}</td>
                    <td class="py-3 px-4 text-right space-x-2">
                        <a href="{{ route('sekolah.edit', $s) }}" class="text-blue-600 hover:underline">Edit</a>
                        <form action="{{ route('sekolah.destroy', $s) }}" method="POST" class="inline"
                              onsubmit="return confirm('Yakin hapus sekolah ini? Data siswa di sekolah ini akan ikut terhapus.');">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="py-6 px-4 text-center text-gray-400">Belum ada data sekolah.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $sekolahs->appends(['desa_id' => $desaId])->links() }}
</div>
@endsection
