<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Kecamatan')</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen">
@auth
<div class="flex min-h-screen">
    <aside class="w-64 bg-gray-900 text-gray-200 hidden md:flex flex-col">
        <div class="px-6 py-5 text-lg font-bold text-white border-b border-gray-800">
            Dashboard Kecamatan
        </div>
        <nav class="flex-1 px-3 py-4 space-y-1 text-sm">
            <a href="{{ route('dashboard') }}"
               class="block px-3 py-2 rounded {{ request()->routeIs('dashboard') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                Dashboard
            </a>

            @if (auth()->user()->isAdmin())
                <a href="{{ route('desa.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('desa.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                    Data Desa
                </a>
                <a href="{{ route('sekolah.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('sekolah.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                    Data Sekolah
                </a>
                <a href="{{ route('anak.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('anak.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                    Data Anak &amp; Stunting
                </a>
                <a href="{{ route('faskes.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('faskes.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                    Fasilitas Kesehatan
                </a>
                <span class="block px-3 py-2 rounded opacity-40 cursor-not-allowed">Manajemen User (segera)</span>
            @elseif (auth()->user()->isUserSekolah())
                <a href="{{ route('sekolah.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('sekolah.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                    Sekolah Saya
                </a>
            @elseif (auth()->user()->isPuskesmas())
                <a href="{{ route('faskes.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('faskes.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                    Faskes Saya
                </a>
                <a href="{{ route('anak.index') }}"
                   class="block px-3 py-2 rounded {{ request()->routeIs('anak.*') ? 'bg-gray-800 text-white' : 'hover:bg-gray-800' }}">
                    Data Anak &amp; Stunting
                </a>
            @endif
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <nav class="bg-white shadow px-6 py-4 flex items-center justify-between">
            <span class="font-semibold text-gray-800 md:hidden">Dashboard Kecamatan</span>
            <span class="hidden md:block"></span>
            <div class="flex items-center gap-4">
                <span class="text-sm text-gray-600">
                    {{ auth()->user()->name }}
                    <span class="text-xs bg-gray-200 rounded px-2 py-0.5 ml-1">{{ auth()->user()->role }}</span>
                </span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-red-600 hover:underline">Logout</button>
                </form>
            </div>
        </nav>

        <main class="p-6 flex-1">
            @yield('content')
        </main>
    </div>
</div>
@else
<main class="p-6 max-w-7xl mx-auto">
    @yield('content')
</main>
@endauth
</body>
</html>
