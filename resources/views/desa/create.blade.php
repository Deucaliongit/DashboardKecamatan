@extends('layouts.app')

@section('title', 'Tambah Desa')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Tambah Desa</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-md">
    <form method="POST" action="{{ route('desa.store') }}">
        @csrf
        @include('desa._form')

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
                Simpan
            </button>
            <a href="{{ route('desa.index') }}" class="text-sm text-gray-600 px-4 py-2 hover:underline">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
