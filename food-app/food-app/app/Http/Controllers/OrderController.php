<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    // 1. Tambah ke Keranjang (Session)
    public function addToCart(Request $request, $id) // Tambahkan Request $request
    {
        $product = Product::findOrFail($id);
        $cart = session()->get('cart', []);

        // Ambil jumlah dari input, defaultnya 1 jika kosong
        // (int) memastikan datanya berupa angka bulat
        $qty = (int) $request->input('quantity', 1);

        // Validasi minimal pesan 1
        if($qty < 1) $qty = 1;

        if(isset($cart[$id])) {
            // Jika sudah ada, tambahkan dengan jumlah yang diinput user
            $cart[$id]['quantity'] += $qty;
        } else {
            // Jika belum ada, masukkan data baru dengan jumlah tersebut
            $cart[$id] = [
                "name" => $product->name,
                "quantity" => $qty, // Pakai variabel $qty
                "price" => $product->price,
                "image" => $product->image
            ];
        }

        session()->put('cart', $cart);
        return redirect()->back()->with('success', "Berhasil menambahkan $qty porsi {$product->name}!");
    }
    // 2. Halaman Checkout
    public function checkout()
    {
        $cart = session()->get('cart');
        if(!$cart) {
            return redirect()->route('front.index')->with('error', 'Keranjang masih kosong!');
        }

        // 1. AMBIL DAFTAR MEJA YANG SEDANG DIPAKAI
        // Logika: Cari order yang statusnya BELUM 'completed' dan BELUM 'cancel'
        // Dan tipe ordernya harus 'dine_in' (Meja ...)
        $occupiedTables = Order::whereIn('status', ['pending', 'cooking'])
                            ->where('table_number', 'LIKE', 'Meja %') // Ambil yang formatnya "Meja X"
                            ->pluck('table_number')
                            ->toArray();
        // Hasilnya nanti array: ['Meja 1', 'Meja 3', 'Meja 9']

        return view('front.checkout', compact('cart', 'occupiedTables'));
    }

    // 3. Proses Simpan Pesanan ke Database
    public function store(Request $request)
    {
        // 1. Validasi
        // Validasi table_number kita buat dinamis
    // 1. Cek Validasi Dasar
    $request->validate([
        'customer_name' => 'required|string|max:255',
        'order_type' => 'required|in:dine_in,takeaway,pickup',
    ]);

    // 2. Validasi Khusus Meja (Cek lagi apakah beneran kosong)
    if ($request->order_type == 'dine_in') {

        // Pastikan formatnya "Meja X"
        if (empty($request->table_number)) {
            return redirect()->back()->with('error', 'Silakan pilih nomor meja!');
        }

        // Cek di database apakah meja ini sedang dipakai (status pending/cooking)
        $isOccupied = Order::where('table_number', $request->table_number)
                           ->whereIn('status', ['pending', 'cooking'])
                           ->exists();

        if ($isOccupied) {
            return redirect()->back()->with('error', 'Maaf, meja tersebut baru saja ditempati orang lain. Silakan pilih meja lain.');
        }
    }
        $cart = session()->get('cart');

        // Cek keranjang kosong
        if(!$cart) {
            return redirect()->route('front.index')->with('error', 'Keranjang kosong!');
        }

        $totalAmount = 0;
        foreach($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // 2. Tentukan Isi Kolom Table Number
        // Jika Dine In -> Pakai input user (misal "Meja 5")
        // Jika Bungkus/Pick Up -> Kita isi otomatis textnya jadi "Bungkus" atau "Pick Up"
        if ($request->order_type == 'dine_in') {
            $tableInfo = $request->table_number;
        } elseif ($request->order_type == 'takeaway') {
            $tableInfo = 'Bungkus (Takeaway)';
        } else {
            $tableInfo = 'Pick Up';
        }

        DB::transaction(function () use ($request, $cart, $totalAmount, $tableInfo) {

            // Simpan Order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'table_number' => $tableInfo, // <--- Pakai variabel yang sudah diolah
                'total_price' => $totalAmount,
                'status' => 'pending'
            ]);

            // Simpan Detail Item
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $id,
                    'quantity' => $details['quantity'],
                    'price' => $details['price']
                ]);
            }
        });

        session()->forget('cart');
        return redirect()->route('front.index')->with('success', 'Pesanan berhasil dibuat!');
    }

    // ... method addToCart dan checkout yang sudah ada ...

    // 3. UPDATE: Ubah Jumlah Pesanan di Keranjang
    public function updateCart(Request $request)
    {
        if($request->id && $request->quantity){
            $cart = session()->get('cart');

            // Update jumlahnya
            $cart[$request->id]["quantity"] = $request->quantity;

            // Simpan balik ke session
            session()->put('cart', $cart);

            return redirect()->back()->with('success', 'Jumlah pesanan berhasil diubah!');
        }
    }

    // 4. DELETE: Hapus Item dari Keranjang
    public function removeFromCart($id)
    {
        $cart = session()->get('cart');

        if(isset($cart[$id])) {
            // Hapus item dari array session
            unset($cart[$id]);

            // Simpan balik
            session()->put('cart', $cart);
        }

        return redirect()->back()->with('success', 'Menu berhasil dihapus dari keranjang!');
    }

    // ... method store ...
}
