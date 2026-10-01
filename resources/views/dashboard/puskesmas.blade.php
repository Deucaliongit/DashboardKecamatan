@extends('layouts.app')

@section('title', 'Dashboard Puskesmas')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-1">Dashboard Puskesmas</h1>
<p class="text-gray-600 mb-6">{{ $faskes->nama_faskes ?? 'Akun Anda belum terhubung ke fasilitas kesehatan manapun.' }}</p>

@if ($faskes)
<div class="grid grid-cols-1 md:grid-cols-3 gap-4">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Jumlah Tenaga Medis</p>
        <p class="text-2xl font-bold text-blue-600">{{ $faskes->jumlah_tenaga_medis }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Anak Terdata</p>
        <p class="text-2xl font-bold text-emerald-600">{{ $totalAnakTerdata }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Anak Stunting</p>
        <p class="text-2xl font-bold text-red-600">{{ $totalStunting }} <span class="text-sm text-gray-400">({{ $persentaseStunting }}%)</span></p>
    </div>
</div>

<p class="text-sm text-gray-500 mt-6">
    Kelola data anak &amp; stunting lewat menu "Data Anak &amp; Stunting", dan update jumlah tenaga medis lewat "Faskes Saya" di sidebar.
</p>
@endif
@endsection
