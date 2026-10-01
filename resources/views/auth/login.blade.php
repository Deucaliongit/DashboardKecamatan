<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Dashboard Kecamatan</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-md w-full max-w-sm">
        <h1 class="text-xl font-bold mb-1 text-gray-800 text-center">Dashboard Terpadu</h1>
        <p class="text-sm text-gray-500 text-center mb-6">Pendidikan &amp; Kesehatan Tingkat Kecamatan</p>

        @if ($errors->any())
            <div class="mb-4 text-sm text-red-600 bg-red-50 border border-red-200 rounded p-3">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('login') }}" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required autofocus
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Password</label>
                <input type="password" name="password" required
                       class="w-full border border-gray-300 rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit"
                    class="w-full bg-blue-600 text-white py-2 rounded text-sm font-medium hover:bg-blue-700">
                Masuk
            </button>
        </form>

        <p class="text-xs text-gray-400 text-center mt-6">
            Admin: admin@kecamatan.go.id / password
        </p>
    </div>
</body>
</html>
