@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-10">
    <div class="flex justify-between items-center mb-6">
        <div>
            <h1 class="text-3xl font-bold text-gray-800">👨‍🍳 Dashboard Dapur</h1>
            <p class="text-gray-500 text-sm">Kelola pesanan dan menu restoran.</p>
        </div>

        <div class="flex gap-3">
            <a href="{{ route('admin.products.index') }}" class="bg-white border border-gray-300 text-gray-700 px-5 py-2 rounded-lg font-bold hover:bg-gray-50 transition shadow-sm">
                📋 Lihat Menu
            </a>

            <a href="{{ route('admin.products.create') }}" class="bg-orange-600 text-white px-5 py-2 rounded-lg font-bold hover:bg-orange-700 transition shadow flex items-center gap-2">
                <span>+ Tambah Menu</span>
            </a>

        </div>
    </div>
    <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
        <table class="w-full text-left border-collapse">
            <thead class="bg-gray-100 text-gray-600 uppercase text-sm font-bold">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Pemesan / Meja</th>
                    <th class="p-4">Detail Pesanan</th>
                    <th class="p-4">Total</th>
                    <th class="p-4">Status</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-200">
                @foreach($orders as $order)
                <tr class="hover:bg-gray-50 transition">

                    <td class="p-4 align-top">
                        <div class="font-mono text-xs text-gray-500 mb-1">#{{ $order->id }}</div>
                        @if($order->table_number == 'Bungkus (Takeaway)' || $order->table_number == 'Pick Up')
                            <span class="bg-blue-100 text-blue-700 text-xs font-bold px-2 py-1 rounded">
                                🎁 Takeaway
                            </span>
                        @else
                            <span class="bg-orange-100 text-orange-700 text-xs font-bold px-2 py-1 rounded">
                                🍽️ Dine In
                            </span>
                        @endif
                    </td>

                    <td class="p-4 align-top">
                        <div class="font-bold text-gray-800">{{ $order->customer_name }}</div>
                        <div class="text-sm text-gray-500 font-semibold mt-1">
                            {{ $order->table_number }} </div>
                        <div class="text-xs text-gray-400 mt-1">
                            {{ $order->created_at->diffForHumans() }}
                        </div>
                    </td>

                    <td class="p-4 align-top">
                        <ul class="text-sm text-gray-600 space-y-1">
                            @foreach($order->items as $item)
                            <li class="flex justify-between">
                                <span>{{ $item->product->name }} <span class="text-gray-400">x{{ $item->quantity }}</span></span>
                            </li>
                            @endforeach
                        </ul>
                    </td>

                    <td class="p-4 align-top">
                        <div class="font-bold text-gray-800">Rp {{ number_format($order->total_price, 0, ',', '.') }}</div>

                        <div class="mt-2">
                            @if($order->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded-full text-xs font-bold">⏳ Pending</span>
                            @elseif($order->status == 'cooking')
                                <span class="bg-blue-100 text-blue-800 px-2 py-1 rounded-full text-xs font-bold">🔥 Dimasak</span>
                            @elseif($order->status == 'completed')
                                <span class="bg-green-100 text-green-800 px-2 py-1 rounded-full text-xs font-bold">✅ Selesai</span>
                            @else
                                <span class="bg-red-100 text-red-800 px-2 py-1 rounded-full text-xs font-bold">❌ Batal</span>
                            @endif
                        </div>
                    </td>

                    <td class="p-4 align-top text-center">
                        <div class="flex flex-col gap-2">

                            @if($order->status == 'pending')
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cooking">
                                    <button type="submit" class="w-full bg-blue-500 hover:bg-blue-600 text-white text-xs font-bold py-2 rounded shadow">
                                        👨‍🍳 Proses Masak
                                    </button>
                                </form>

                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="cancel">
                                    <button type="submit" class="w-full bg-red-100 hover:bg-red-200 text-red-600 text-xs font-bold py-2 rounded" onclick="return confirm('Tolak pesanan ini?')">
                                        Tolak
                                    </button>
                                </form>

                            @elseif($order->status == 'cooking')
                                <form action="{{ route('admin.orders.status', $order->id) }}" method="POST">
                                    @csrf @method('PATCH')
                                    <input type="hidden" name="status" value="completed">
                                    <button type="submit" class="w-full bg-green-500 hover:bg-green-600 text-white text-xs font-bold py-2 rounded shadow animate-pulse">
                                        ✅ Pesanan Selesai
                                    </button>
                                </form>

                            @else
                                <span class="text-gray-400 text-xs">-</span>
                            @endif

                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
