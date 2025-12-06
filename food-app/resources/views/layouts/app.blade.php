<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FoodOrder - Pesan Makan Gampang</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">

    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <a href="{{ route('front.index') }}" class="text-2xl font-bold text-orange-600 flex items-center gap-2">
                🍔 FoodOrder
            </a>

            <a href="{{ route('cart.checkout') }}" class="relative p-2 hover:bg-gray-100 rounded-full transition">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7 text-gray-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                </svg>
                @if(session('cart'))
                <span class="absolute top-0 right-0 bg-red-500 text-white text-xs font-bold rounded-full h-5 w-5 flex items-center justify-center">
                    {{ count(session('cart')) }}
                </span>
                @endif
            </a>
        </div>
    </nav>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-6 text-center mt-12">
        <p>&copy; {{ date('Y') }} FoodOrder App.</p>
        <a href="{{ route('login') }}" class="text-gray-600 text-xs hover:text-white transition">Admin Login</a>
    </footer>

</body>
</html>
