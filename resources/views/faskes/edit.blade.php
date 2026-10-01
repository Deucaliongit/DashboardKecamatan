@extends('layouts.app')

@section('title', 'Edit Fasilitas Kesehatan')

@section('content')
<h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Fasilitas Kesehatan</h1>

@if (session('success'))
    <div class="mb-4 bg-green-50 border border-green-200 text-green-700 text-sm rounded p-3 max-w-md">{{ session('success') }}</div>
@endif

<div class="bg-white rounded-lg shadow p-6 max-w-md">
    <form method="POST" action="{{ route('faskes.update', $faskes) }}">
        @csrf
        @method('PUT')
        @include('faskes._form')

        <div class="flex gap-3 mt-6">
            <button type="submit" class="bg-blue-600 text-white text-sm px-4 py-2 rounded hover:bg-blue-700">
                Simpan Perubahan
            </button>
            @if (auth()->user()->isAdmin())
                <a href="{{ route('faskes.index') }}" class="text-sm text-gray-600 px-4 py-2 hover:underline">
                    Batal
                </a>
            @endif
        </div>
    </form>
</div>
@endsection
