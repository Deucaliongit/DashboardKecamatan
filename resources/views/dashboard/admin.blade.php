@extends('layouts.app')

@section('title', 'Dashboard Admin Kecamatan')

@section('content')
<div class="flex flex-wrap items-center justify-between gap-3 mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin Kecamatan</h1>

    <form method="GET" class="flex items-center gap-2">
        <label class="text-sm text-gray-600">Filter Desa:</label>
        <select name="desa_id" onchange="this.form.submit()" class="border border-gray-300 rounded px-3 py-1.5 text-sm">
            <option value="">Semua Desa</option>
            @foreach ($desas as $desa)
                <option value="{{ $desa->id }}" @selected($desaId == $desa->id)>{{ $desa->nama_desa }}</option>
            @endforeach
        </select>
    </form>
</div>

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded p-3">{{ session('success') }}</div>
@endif

<h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">Pendidikan</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Sekolah</p>
        <p class="text-3xl font-bold text-blue-600">{{ $totalSekolah }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Guru</p>
        <p class="text-3xl font-bold text-blue-600">{{ $totalGuru }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Siswa</p>
        <p class="text-3xl font-bold text-blue-600">{{ $totalSiswa }}</p>
    </div>
</div>

<h2 class="text-sm font-semibold text-gray-500 uppercase mb-2">Kesehatan</h2>
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-8">
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Puskesmas / Posyandu</p>
        <p class="text-3xl font-bold text-emerald-600">{{ $totalFaskes }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Anak Terdata</p>
        <p class="text-3xl font-bold text-emerald-600">{{ $totalAnakTerdata }}</p>
    </div>
    <div class="bg-white rounded-lg shadow p-5">
        <p class="text-sm text-gray-500">Total Anak Stunting</p>
        <p class="text-3xl font-bold text-red-600">{{ $totalStunting }}</p>
    </div>
</div>

<div class="bg-white rounded-lg shadow p-5 mb-8 overflow-x-auto">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Rekap Per Desa</h2>
    <table class="w-full text-sm text-left">
        <thead class="text-gray-500 border-b">
            <tr>
                <th class="py-2 pr-4">Desa</th>
                <th class="py-2 pr-4">Sekolah</th>
                <th class="py-2 pr-4">Guru</th>
                <th class="py-2 pr-4">Siswa</th>
                <th class="py-2 pr-4">Anak Terdata</th>
                <th class="py-2 pr-4">Stunting</th>
                <th class="py-2 pr-4">% Stunting</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($rekapPerDesa as $desa)
                <tr class="border-b last:border-0">
                    <td class="py-2 pr-4 font-medium text-gray-800">{{ $desa->nama_desa }}</td>
                    <td class="py-2 pr-4">{{ $desa->sekolahs_count }}</td>
                    <td class="py-2 pr-4">{{ $desa->total_guru ?? 0 }}</td>
                    <td class="py-2 pr-4">{{ ($desa->total_siswa_l ?? 0) + ($desa->total_siswa_p ?? 0) }}</td>
                    <td class="py-2 pr-4">{{ $desa->total_anak_terdata }}</td>
                    <td class="py-2 pr-4 text-red-600">{{ $desa->total_stunting }}</td>
                    <td class="py-2 pr-4">
                        {{ $desa->total_anak_terdata > 0 ? round(($desa->total_stunting / $desa->total_anak_terdata) * 100, 1) : 0 }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>

<div class="bg-white rounded-lg shadow p-5">
    <h2 class="text-lg font-semibold text-gray-800 mb-4">Grafik Anak Stunting per Desa</h2>
    <canvas id="chartStunting" height="90"></canvas>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.0/chart.umd.min.js"></script>
<script>
    new Chart(document.getElementById('chartStunting'), {
        type: 'bar',
        data: {
            labels: {!! json_encode($rekapPerDesa->pluck('nama_desa')) !!},
            datasets: [
                {
                    label: 'Normal',
                    data: {!! json_encode($rekapPerDesa->map(fn ($d) => $d->total_anak_terdata - $d->total_stunting)) !!},
                    backgroundColor: '#3b82f6'
                },
                {
                    label: 'Stunting',
                    data: {!! json_encode($rekapPerDesa->pluck('total_stunting')) !!},
                    backgroundColor: '#ef4444'
                }
            ]
        },
        options: {
            responsive: true,
            scales: { x: { stacked: true }, y: { stacked: true, beginAtZero: true } }
        }
    });
</script>
@endsection
