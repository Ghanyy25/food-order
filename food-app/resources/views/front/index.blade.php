@extends('layouts.app')

@section('content')

@if(session('success'))
<div class="bg-green-500 text-white text-center py-3 font-semibold animate-pulse">
    {{ session('success') }}
</div>
@endif

<div class="bg-gradient-to-r from-orange-500 to-red-500 py-20 text-white text-center rounded-b-[3rem] shadow-xl mb-12 relative overflow-hidden">
    <div class="relative z-10">
        <h1 class="text-4xl md:text-6xl font-bold mb-4 drop-shadow-md">Lapar? Pesan Sekarang!</h1>
        <p class="text-lg md:text-xl opacity-90 mb-8">Makanan lezat siap diantar ke meja Anda.</p>
    </div>
    <div class="absolute top-0 left-0 w-64 h-64 bg-white opacity-10 rounded-full -translate-x-1/2 -translate-y-1/2"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-white opacity-10 rounded-full translate-x-1/3 translate-y-1/3"></div>
</div>

<div class="container mx-auto px-4 pb-20">

    <div class="flex gap-4 overflow-x-auto pb-4 mb-8 no-scrollbar">

        <a href="{{ route('front.index') }}"
           class="px-6 py-2 rounded-full font-semibold whitespace-nowrap transition shadow-sm
           {{ !request('category') ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            Semua
        </a>

        @foreach($categories as $cat)
        <a href="{{ route('front.index', ['category' => $cat->slug]) }}"
           class="px-6 py-2 rounded-full font-semibold whitespace-nowrap transition shadow-sm
           {{ request('category') == $cat->slug ? 'bg-orange-600 text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">
            {{ $cat->name }}
        </a>
        @endforeach

    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
        @forelse($products as $product)
        <div class="bg-white rounded-2xl shadow-lg hover:shadow-2xl transition duration-300 overflow-hidden border border-gray-100 flex flex-col h-full">
            <div class="h-56 bg-gray-200 relative overflow-hidden group">
                <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/600x400?text=No+Image' }}"
                     alt="{{ $product->name }}"
                     class="w-full h-full object-cover group-hover:scale-110 transition duration-500">
                <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-orange-600 px-3 py-1 rounded-full text-sm font-bold shadow-sm border border-orange-100">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </span>
            </div>

            <div class="p-6 flex-grow flex flex-col">
                <div class="flex-grow">
                    <h3 class="text-xl font-bold text-gray-800 mb-2">{{ $product->name }}</h3>
                    <p class="text-gray-500 text-sm line-clamp-2 mb-4">{{ $product->description }}</p>
                </div>

                <form action="{{ route('cart.add', $product->id) }}" method="POST" class="mt-auto">
                    @csrf

                    <div class="flex items-center gap-2">
                        <input type="number"
                            name="quantity"
                            value="1"
                            min="1"
                            class="w-16 px-2 py-3 text-center border border-gray-300 rounded-xl focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none font-bold text-gray-700">

                        <button type="submit" class="flex-1 bg-gray-900 text-white font-bold py-3 rounded-xl hover:bg-orange-600 transition-colors flex items-center justify-center gap-2">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            <span>Pesan</span>
                        </button>
                    </div>

                </form>
            </div>
        </div>
        @empty
        <div class="col-span-full text-center py-20">
            <div class="text-gray-400 mb-2 text-6xl">🍽️</div>
            <p class="text-gray-500 text-lg">Belum ada menu untuk kategori ini.</p>
            <a href="{{ route('front.index') }}" class="text-orange-600 font-bold text-sm mt-2 hover:underline">Lihat Semua Menu</a>
        </div>
        @endforelse
    </div>
</div>

<style>
    /* Sembunyikan scrollbar tapi tetap bisa scroll */
    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }
</style>
@endsection
