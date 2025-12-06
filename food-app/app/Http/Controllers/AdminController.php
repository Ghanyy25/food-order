<?php
namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;  // <--- Tambahkan ini
use App\Models\Category; // <--- Tambahkan ini
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <--- Tambahkan ini untuk hapus/simpan gambar

class AdminController extends Controller
{
    public function index()
    {
        $orders = Order::with('items.product')->latest()->get();
        return view('admin.dashboard', compact('orders'));
    }

    // 1. Menampilkan Form Tambah Produk
    public function create()
    {
        $categories = Category::all(); // Ambil kategori buat dropdown
        return view('admin.products.create', compact('categories'));
    }

    // 2. Proses Simpan ke Database
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048', // Max 2MB
        ]);

        // Upload Gambar
        $imagePath = $request->file('image')->store('products', 'public');

        // Simpan Data Produk
        Product::create([
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            'image' => $imagePath,
            'is_available' => true,
        ]);

        return redirect()->route('dashboard')->with('success', 'Menu berhasil ditambahkan!');
    }

    public function products()
    {
        // 1. Ambil semua produk beserta data kategorinya
        // 2. Kelompokkan (Group By) berdasarkan nama kategori
        $groupedProducts = Product::with('category')->get()->groupBy('category.name');

        return view('admin.products.index', compact('groupedProducts'));
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $categories = Category::all();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        // Validasi (Image jadi 'nullable' karena user mungkin gak ganti gambar)
        $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'is_available' => 'boolean' // Validasi status ketersediaan
        ]);

        // Siapkan data yang mau diupdate
        $data = [
            'name' => $request->name,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'description' => $request->description,
            // Ambil nilai is_available dari checkbox (jika dicentang kirim 1, jika tidak 0)
            'is_available' => $request->has('is_available')
        ];

        // Logika Ganti Gambar
        if ($request->hasFile('image')) {
            // 1. Hapus gambar lama jika ada
            if ($product->image) {
                Storage::disk('public')->delete($product->image);
            }
            // 2. Simpan gambar baru
            $data['image'] = $request->file('image')->store('products', 'public');
        }

        // Update database
        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        // Hapus file gambar dari folder 'storage/app/public/products'
        if ($product->image) {
            // Kita pakai disk 'public' karena saat upload kita pakai store('...', 'public')
            Storage::disk('public')->delete($product->image);
        }

        // Hapus data dari database
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Menu berhasil dihapus!');
    }

    // ... method index, create, store, dll ...

    // METHOD BARU: Update Status Pesanan
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,cooking,completed,cancel'
        ]);

        $order = Order::findOrFail($id);
        $order->update(['status' => $request->status]);

        // Pesan notifikasi sesuai status
        $msg = 'Status pesanan diperbarui!';
        if($request->status == 'cooking') $msg = 'Pesanan masuk dapur! 👨‍🍳';
        if($request->status == 'completed') $msg = 'Pesanan selesai. Meja kembali kosong! ✅';

        return redirect()->back()->with('success', $msg);
    }
}
