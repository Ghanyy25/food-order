@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="max-w-2xl mx-auto bg-white p-8 rounded-2xl shadow-lg border border-gray-100">

        <h2 class="text-2xl font-bold mb-6 text-gray-800">🍳 Tambah Menu Baru</h2>

        <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Nama Makanan</label>
                <input type="text" name="name" required
                       class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none">
            </div>

            <div class="grid grid-cols-2 gap-4 mb-4">
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Kategori</label>
                    <select name="category_id" required class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 outline-none">
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                    @if($categories->isEmpty())
                        <p class="text-red-500 text-xs mt-1">*Isi tabel categories dulu di database</p>
                    @endif
                </div>
                <div>
                    <label class="block text-gray-700 font-bold mb-2">Harga (Rp)</label>
                    <input type="number" name="price" required
                           class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 outline-none">
                </div>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-bold mb-2">Deskripsi Singkat</label>
                <textarea name="description" rows="3"
                          class="w-full px-4 py-2 rounded-lg border border-gray-300 focus:border-orange-500 outline-none"></textarea>
            </div>

            <div class="mb-6">
                <label class="block text-gray-700 font-bold mb-2">Foto Makanan</label>
                <input type="file" name="image" required accept="image/*"
                       class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-orange-50 file:text-orange-700 hover:file:bg-orange-100">
            </div>

            <button type="submit" class="w-full bg-orange-600 text-white font-bold py-3 rounded-xl hover:bg-orange-700 transition shadow-lg">
                Simpan Menu
            </button>

            <div class="mt-4 text-center">
                <a href="{{ route('dashboard') }}" class="text-gray-500 text-sm hover:underline">Kembali ke Dashboard</a>
            </div>
        </form>
    </div>
</div>
@endsection
