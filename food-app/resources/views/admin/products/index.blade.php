@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-10">

    <div class="flex justify-between items-center mb-8">
        <h1 class="text-3xl font-bold text-gray-800">📋 Daftar Menu</h1>
        <a href="{{ route('admin.products.create') }}" class="bg-orange-600 text-white px-5 py-2 rounded-lg font-bold hover:bg-orange-700 transition shadow-lg flex items-center gap-2">
            <span>+ Tambah Menu Baru</span>
        </a>
    </div>

    @forelse($groupedProducts as $categoryName => $products)

        <div class="mb-10">
            <h2 class="text-xl font-bold text-orange-600 mb-4 border-l-4 border-orange-500 pl-3 flex items-center gap-2">
                <span>📂</span> {{ $categoryName }}
            </h2>

            <div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-gray-50 text-gray-600 uppercase text-xs font-bold tracking-wider">
                        <tr>
                            <th class="p-4 w-24">Gambar</th>
                            <th class="p-4">Nama Menu</th>
                            <th class="p-4 w-40">Harga</th>
                            <th class="p-4 w-32 text-center">Status</th>
                            <th class="p-4 w-32 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100">
                        @foreach($products as $product)
                        <tr class="hover:bg-orange-50 transition">
                            <td class="p-4">
                                <img src="{{ Storage::url($product->image)  ? asset('storage/'.$product->image) : 'https://placehold.co/100' }}"
                                     class="w-16 h-16 object-cover rounded-lg border border-gray-200 shadow-sm">
                            </td>
                            <td class="p-4">
                                <div class="font-bold text-gray-800">{{ $product->name }}</div>
                                <div class="text-xs text-gray-500 line-clamp-1">{{ $product->description }}</div>
                            </td>
                            <td class="p-4 font-semibold text-gray-700">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </td>
                            <td class="p-4 text-center">
                                @if($product->is_available)
                                    <span class="bg-green-100 text-green-700 px-3 py-1 rounded-full text-xs font-bold">Tersedia</span>
                                @else
                                    <span class="bg-red-100 text-red-700 px-3 py-1 rounded-full text-xs font-bold">Habis</span>
                                @endif
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex justify-center gap-2">
                                    <a href="{{ route('admin.products.edit', $product->id) }}"
                                    class="text-blue-500 hover:text-blue-700 font-bold text-sm mr-2">
                                    Edit
                                    </a>

                                    <form action="{{ route('admin.products.destroy', $product->id) }}" method="POST" class="inline-block" onsubmit="return confirm('Yakin ingin menghapus menu ini?');">
                                        @csrf
                                        @method('DELETE') <button type="submit" class="text-red-500 hover:text-red-700 font-bold text-sm ml-2">
                                            Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

    @empty
        <div class="text-center py-20 bg-white rounded-2xl border border-dashed border-gray-300">
            <p class="text-gray-500 text-lg mb-4">Belum ada menu yang ditambahkan.</p>
            <a href="{{ route('admin.products.create') }}" class="text-orange-600 font-bold hover:underline">
                Mulai tambah menu sekarang
            </a>
        </div>
    @endforelse

</div>
@endsection
