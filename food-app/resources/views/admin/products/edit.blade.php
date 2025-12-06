@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-100">

        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">✏️ Edit Menu</h2>
            </div>

        <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT') <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Makanan</label>
                <input type="text" name="name" value="{{ old('name', $product->name) }}" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 outline-none">
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                {{ $cat->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price', $product->price) }}" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi Singkat</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 outline-none">{{ old('description', $product->description) }}</textarea>
            </div>

            <div class="mb-6 bg-gray-50 p-4 rounded-lg border border-gray-200 flex items-center justify-between">
                <span class="text-gray-700 font-bold">Status Menu</span>
                <label class="relative inline-flex items-center cursor-pointer">
                    <input type="checkbox" name="is_available" value="1" class="sr-only peer" {{ $product->is_available ? 'checked' : '' }}>
                    <div class="w-11 h-6 bg-gray-200 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-green-500"></div>
                    <span class="ml-3 text-sm font-medium text-gray-900">Tersedia</span>
                </label>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Ganti Foto (Opsional)</label>
                <div class="flex items-center gap-4">
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : 'https://placehold.co/100' }}"
                         class="w-20 h-20 object-cover rounded-lg border border-gray-300">

                    <input type="file" name="image" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
                </div>
                <p class="text-xs text-gray-500 mt-1">*Biarkan kosong jika tidak ingin mengganti foto.</p>
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-3 rounded-xl hover:bg-blue-700 transition shadow-lg">
                Update Perubahan
            </button>

            <div class="mt-4 text-center">
                <a href="{{ route('admin.products.index') }}" class="text-gray-500 text-sm hover:underline">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection
