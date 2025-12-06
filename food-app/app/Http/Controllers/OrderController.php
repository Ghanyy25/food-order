<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

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
        // 1. Validasi Dasar
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'order_type'    => 'required|in:dine_in,takeaway,pickup',
        ]);

        // 2. Validasi Meja untuk Dine In
        if ($request->order_type == 'dine_in') {
            if (empty($request->table_number)) {
                return back()->with('error', 'Silakan pilih nomor meja!');
            }

            $isOccupied = Order::where('table_number', $request->table_number)
                            ->whereIn('status', ['pending', 'cooking'])
                            ->exists();

            if ($isOccupied) {
                return back()->back()->with('error', 'Maaf, meja tersebut baru saja ditempati orang lain. Silakan pilih meja lain.');
            }
        }

        $cart = session()->get('cart');
        if (!$cart) {
            return redirect()->route('front.index')->with('error', 'Keranjang kosong!');
        }

        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Tentukan info meja
        $tableInfo = match ($request->order_type) {
            'dine_in' => $request->table_number,
            'takeaway' => 'Bungkus (Takeaway)',
            default    => 'Pick Up'
        };

        DB::transaction(function () use ($request, $cart, $totalAmount, $tableInfo, &$orderId) {

            // Simpan Order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'table_number'  => $tableInfo,
                'total_price'   => $totalAmount,
                'status'        => 'pending'
            ]);

            $orderId = $order->id; // simpan ID buat kirim WA

            // Simpan Detail Item
            foreach ($cart as $id => $details) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $id,
                    'quantity'   => $details['quantity'],
                    'price'      => $details['price']
                ]);
            }
        });

        // HAPUS KERANJANG
        session()->forget('cart');

        // ================================================
        // KIRIM NOTIFIKASI KE WHATSAPP ADMIN / KASIR
        // ================================================
        try {
            $itemsText = "";
            foreach ($cart as $id => $item) {
                $itemsText .= "• {$item['quantity']}x {$item['name']} @ Rp " . number_format($item['price']) . "\n";
            }

            $message = "PESANAN BARU MASUK!\n\n" .
                    "No. Order : #{$orderId}\n" .
                    "Nama      : {$request->customer_name}\n" .
                    "Tipe      : {$request->order_type}\n" .
                    "Meja      : {$tableInfo}\n" .
                    "Total     : Rp " . number_format($totalAmount) . "\n\n" .
                    "Detail Pesanan:\n{$itemsText}\n" .
                    "Waktu     : " . now()->format('d M Y H:i') . "\n\n" .
                    "Segera proses ya!";

            // GANTI DENGAN NOMOR ADMIN / KASIR (bisa lebih dari satu)
            $adminNumbers = [
                 // nomor kasir 2 (hapus baris ini kalau cuma satu)
            ];

            foreach ($adminNumbers as $number) {
                Http::withHeaders([
                    'Authorization' => env('FONNTE_API_KEY'), // token kamu yang sudah jalan
                ])->asForm()->post('https://api.fonnte.com/send', [
                    'target'      => $number,
                    'message'     => $message,
                    'countryCode' => '62',
                ]);
            }
        } catch (\Exception $e) {
            // Kalau gagal kirim WA, tetap lanjut (jangan sampai order gagal)
            \Log::warning('WA gagal terkirim: ' . $e->getMessage());
        }

        return redirect()->route('front.index')->with('success', 'Pesanan berhasil dibuat! Admin sudah diberi tahu via WhatsApp.');
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
