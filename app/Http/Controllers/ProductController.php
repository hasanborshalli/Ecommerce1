<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::active()->with('category');

        // Filter by category slug
        if ($request->filled('category')) {
            $query->whereHas('category', fn($q) => $q->where('slug', $request->category));
        }

        // Filter by flags
        if ($request->has('new'))    $query->where('is_new', true);
        if ($request->has('sale'))   $query->where('is_on_sale', true);

        // Price range
        if ($request->filled('min_price')) $query->where('price', '>=', $request->min_price);
        if ($request->filled('max_price')) $query->where('price', '<=', $request->max_price);

        // Sorting
        match($request->get('sort', 'default')) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'newest'     => $query->orderBy('created_at', 'desc'),
            default      => $query->orderBy('sort_order'),
        };

        $products   = $query->paginate(12)->withQueryString();
        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $meta = [
            'title'       => 'Shop — ' . SiteSetting::get('site_name'),
            'description' => 'Browse our full collection of curated products.',
        ];

        return view('shop', compact('products', 'categories', 'meta'));
    }

    public function category(\App\Models\Category $category)
    {
        abort_unless($category->is_active, 404);

        $products = Product::active()
            ->where('category_id', $category->id)
            ->orderBy('sort_order')
            ->paginate(12);

        $categories = Category::where('is_active', true)->orderBy('sort_order')->get();

        $meta = [
            'title'       => ($category->meta_title ?: $category->name) . ' — ' . SiteSetting::get('site_name'),
            'description' => $category->meta_description ?: $category->description,
        ];

        return view('shop', compact('products', 'categories', 'category', 'meta'));
    }

    public function show(Product $product)
    {
        abort_unless($product->is_active, 404);

        $product->load('category');

        $related = Product::active()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->take(4)
            ->get();

        $meta = [
            'title'       => ($product->meta_title ?: $product->name) . ' — ' . SiteSetting::get('site_name'),
            'description' => $product->meta_description ?: $product->short_description,
            'image'       => $product->main_image ? asset('storage/' . $product->main_image) : null,
        ];

        return view('product', compact('product', 'related', 'meta'));
    }
}