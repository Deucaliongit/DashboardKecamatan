@extends('layouts.app')

@section('title', 'Dashboard Sekolah')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-1">Dashboard Sekolah</h1>
<p class="text-gray-600 mb-6">{{ $sekolah->nama_sekolah ?? 'Akun Anda belum terhubung ke sekolah manapun.' }}</p>

@if ($sekolah)
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Jenjang</p>
        <p class="text-2xl font-bold text-blue-600">{{ $sekolah->jenjang }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Jumlah Guru</p>
        <p class="text-2xl font-bold text-blue-600">{{ $sekolah->jumlah_guru }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Siswa</p>
        <p class="text-2xl font-bold text-blue-600">{{ $sekolah->jumlah_siswa }}</p>
    </div>
</div>

<div class="grid grid-cols-2 gap-4 max-w-md">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Siswa Laki-laki</p>
        <p class="text-xl font-bold text-gray-800">{{ $sekolah->jumlah_siswa_l }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Siswa Perempuan</p>
        <p class="text-xl font-bold text-gray-800">{{ $sekolah->jumlah_siswa_p }}</p>
    </div>
</div>

<p class="text-sm text-gray-500 mt-6">
    Update jumlah siswa laki-laki/perempuan lewat menu "Sekolah Saya" di sidebar.
</p>
@endif
@endsection
