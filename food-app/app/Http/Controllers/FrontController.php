<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function index(Request $request)
    {
        // Ambil produk, jika ada request 'category', filter datanya
        $products = Product::query()
            ->when($request->category, function ($query) use ($request) {
                return $query->whereHas('category', function ($q) use ($request) {
                    $q->where('slug', $request->category);
                });
            })
            ->where('is_available', true)
            ->get();

        $categories = Category::all();

        return view('front.index', compact('products', 'categories'));
    }
}
