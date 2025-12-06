<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - FoodOrder</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>body { font-family: 'Inter', sans-serif; }</style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">

    <nav class="bg-gray-900 text-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center h-16">

                <div class="flex items-center gap-8">
                    <a href="{{ route('dashboard') }}" class="text-xl font-bold text-orange-500 flex items-center gap-2">
                        🔥 Dapur Admin
                    </a>

                    <div class="hidden md:flex gap-6 text-sm font-medium">
                        <a href="{{ route('dashboard') }}" class="hover:text-orange-400 transition {{ request()->routeIs('dashboard') ? 'text-orange-400' : 'text-gray-300' }}">
                            Ringkasan Order
                        </a>
                        <a href="{{ route('admin.products.index') }}" class="hover:text-orange-400 transition {{ request()->routeIs('admin.products.*') ? 'text-orange-400' : 'text-gray-300' }}">
                            Kelola Menu
                        </a>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <a href="{{ route('front.index') }}" target="_blank" class="text-gray-300 hover:text-white text-sm flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" />
                        </svg>
                        Lihat Website
                    </a>

                    <div class="h-6 w-px bg-gray-700 mx-2"></div>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="bg-red-600 hover:bg-red-700 text-white px-4 py-1.5 rounded-md text-sm font-bold transition">
                            Logout
                        </button>
                    </form>
                </div>

            </div>
        </div>
    </nav>

    <main class="py-8">
        @yield('content')
    </main>

</body>
</html>
