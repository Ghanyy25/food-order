@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4 py-10">

    <h1 class="text-3xl font-bold text-gray-800 mb-8 border-l-8 border-orange-500 pl-4">Selesaikan Pesanan</h1>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">

        <div class="space-y-6">
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h2 class="text-xl font-bold mb-6 flex items-center gap-2">
                    <span class="bg-orange-100 text-orange-600 p-2 rounded-lg">🛒</span>
                    Keranjang Belanja
                </h2>

                <div class="space-y-6">
                    @php $total = 0; @endphp
                    @if(session('cart'))
                        @foreach(session('cart') as $id => $details)
                            @php $total += $details['price'] * $details['quantity']; @endphp

                            <div class="flex flex-col sm:flex-row items-center gap-4 border-b border-gray-100 pb-6 last:border-0">

                                <div class="w-20 h-20 bg-gray-100 rounded-xl overflow-hidden flex-shrink-0 border border-gray-200">
                                    <img src="{{ $details['image'] ? asset('storage/'.$details['image']) : 'https://placehold.co/100' }}"
                                         class="w-full h-full object-cover">
                                </div>

                                <div class="flex-1 text-center sm:text-left">
                                    <h4 class="font-bold text-gray-800 text-lg">{{ $details['name'] }}</h4>
                                    <p class="text-orange-600 font-semibold">
                                        Rp {{ number_format($details['price'], 0, ',', '.') }}
                                    </p>
                                </div>

                                <form action="{{ route('cart.update') }}" method="POST" class="flex items-center">
                                    @csrf
                                    @method('PATCH')
                                    <input type="hidden" name="id" value="{{ $id }}">

                                    <input type="number" name="quantity" value="{{ $details['quantity'] }}" min="1"
                                           class="w-16 text-center border border-gray-300 rounded-l-lg py-1 focus:ring-orange-500 focus:border-orange-500"
                                           onchange="this.form.submit()">
                                    <span class="bg-gray-100 border-t border-r border-b border-gray-300 px-2 py-1 text-sm text-gray-500 rounded-r-lg cursor-default">
                                        Porsi
                                    </span>
                                </form>

                                <form action="{{ route('cart.remove', $id) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="p-2 text-gray-400 hover:text-red-500 hover:bg-red-50 rounded-full transition" title="Hapus Menu">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </form>
                            </div>
                        @endforeach
                    @else
                        <div class="text-center py-10">
                            <p class="text-gray-500">Keranjang kosong.</p>
                            <a href="{{ route('front.index') }}" class="text-orange-600 font-bold hover:underline">Pesan dulu yuk!</a>
                        </div>
                    @endif
                </div>

                <div class="mt-8 pt-6 border-t border-dashed border-gray-300">
                    <div class="flex justify-between items-center text-xl font-bold">
                        <span>Total Pembayaran</span>
                        <span class="text-orange-600">Rp {{ number_format($total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="bg-white p-8 rounded-2xl shadow-lg border border-orange-100 sticky top-24">
                <h2 class="text-xl font-bold mb-6">Informasi Pemesan</h2>

                <form action="{{ route('order.store') }}" method="POST">
                    @csrf

                    <div class="mb-5">
                        <label class="block text-gray-600 font-semibold mb-2">Nama Pemesan</label>
                        <input type="text" name="customer_name" required placeholder="Nama Anda..."
                               class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition">
                    </div>

                    <div class="mb-5">
                        <label class="block text-gray-600 font-semibold mb-2">Tipe Pesanan</label>
                        <select name="order_type" id="orderType" onchange="toggleTableInput()"
                                class="w-full px-4 py-3 rounded-lg border border-gray-300 focus:border-orange-500 focus:ring-2 focus:ring-orange-200 outline-none transition bg-white">
                            <option value="dine_in">🍽️ Makan di Tempat (Dine In)</option>
                            <option value="takeaway">🎁 Bungkus (Takeaway)</option>
                            <option value="pickup">🛵 Ambil Sendiri (Pick Up)</option>
                        </select>
                    </div>

                    <div class="mb-8 hidden" id="tableInputContainer">
                        <label class="block text-gray-600 font-semibold mb-3">Pilih Nomor Meja</label>

                        <input type="hidden" name="table_number" id="selectedTableInput">

                        <div class="grid grid-cols-3 gap-3">
                            @for($i = 1; $i <= 9; $i++)
                                @php
                                    $tableName = "Meja $i";
                                    $isOccupied = in_array($tableName, $occupiedTables);
                                @endphp

                                <button type="button"
                                        onclick="selectTable('{{ $tableName }}', this)"
                                        @if($isOccupied) disabled @endif
                                        class="p-4 rounded-xl border-2 font-bold text-lg transition relative overflow-hidden group
                                        {{ $isOccupied
                                            ? 'bg-red-100 border-red-200 text-red-400 cursor-not-allowed'
                                            : 'bg-white border-gray-200 text-gray-600 hover:border-orange-500 hover:text-orange-500 table-btn'
                                        }}">

                                    {{ $i }}

                                    @if($isOccupied)
                                    <span class="absolute inset-0 flex items-center justify-center bg-red-500/10 text-red-600 text-xs font-bold uppercase tracking-widest">
                                        Penuh
                                    </span>
                                    @endif
                                </button>
                            @endfor
                        </div>
                        <p class="text-xs text-gray-400 mt-2">*Merah berarti meja sedang digunakan.</p>
                    </div>

                    <button type="submit" class="w-full bg-orange-600 text-white font-bold py-4 rounded-xl hover:bg-orange-700 transition shadow-lg hover:shadow-xl transform hover:-translate-y-1">
                        Konfirmasi Pesanan 🚀
                    </button>
                </form>
            </div>
        </div>

    </div> </div> <script>
    // 1. Logic Tampilkan/Sembunyikan Grid Meja
    function toggleTableInput() {
        const type = document.getElementById('orderType').value;
        const tableContainer = document.getElementById('tableInputContainer');
        const tableInput = document.getElementById('selectedTableInput');

        if (type === 'dine_in') {
            tableContainer.classList.remove('hidden');
            tableInput.required = true;
        } else {
            tableContainer.classList.add('hidden');
            tableInput.required = false;
            tableInput.value = ''; // Reset nilai
            resetTableSelection(); // Reset tampilan tombol
        }
    }

    // 2. Logic Memilih Meja (Klik Tombol Grid)
    function selectTable(tableName, btnElement) {
        // Simpan nilai ke input hidden
        document.getElementById('selectedTableInput').value = tableName;

        // Reset semua tombol lain jadi putih
        resetTableSelection();

        // Warnai tombol yang diklik jadi Oranye
        btnElement.classList.remove('bg-white', 'border-gray-200', 'text-gray-600');
        btnElement.classList.add('bg-orange-500', 'border-orange-600', 'text-white', 'shadow-md', 'selected-btn');
    }

    // 3. Fungsi Reset Tampilan (Balikin ke putih semua)
    function resetTableSelection() {
        document.querySelectorAll('.table-btn').forEach(btn => {
            btn.classList.add('bg-white', 'border-gray-200', 'text-gray-600');
            btn.classList.remove('bg-orange-500', 'border-orange-600', 'text-white', 'shadow-md', 'selected-btn');
        });
    }

    // Jalankan sekali saat halaman dimuat (untuk set default state)
    document.addEventListener('DOMContentLoaded', toggleTableInput);
</script>
@endsection
