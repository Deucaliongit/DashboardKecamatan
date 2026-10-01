@extends('layouts.app')

@section('title', 'Edit Data Anak')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Data Anak</h1>

<div class="bg-white rounded-lg shadow p-6 max-w-lg">
    <form method="POST" action="{{ route('anak.update', $anak) }}">
        @csrf
        @method('PUT')
        @include('anak._form')

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
            <a href="{{ route('anak.index') }}" class="text-sm text-gray-600 px-4 py-2 hover:underline">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
