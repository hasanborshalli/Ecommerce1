<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        $products   = $query->orderBy('sort_order')->paginate(20)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('admin.products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.form', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $this->validateProduct($request);
        $data = $this->handleImages($request, $data);
        $data['slug'] = Str::slug($data['name']);

        Product::create($data);

        return redirect()->route('admin.products.index')->with('success', 'Product created.');
    }

    public function edit(Product $product)
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        return view('admin.products.form', compact('product', 'categories'));
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validateProduct($request);
        $data = $this->handleImages($request, $data, $product);
        $data['slug'] = Str::slug($data['name']);

        $product->update($data);

        return redirect()->route('admin.products.index')->with('success', 'Product updated.');
    }

    public function destroy(Product $product)
    {
        if ($product->main_image) {
            Storage::disk('public')->delete($product->main_image);
        }
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    public function toggleActive(Product $product)
    {
        $product->update(['is_active' => !$product->is_active]);
        return response()->json(['is_active' => $product->is_active]);
    }

    private function validateProduct(Request $request): array
    {
        return $request->validate([
            'category_id'       => 'required|exists:categories,id',
            'name'              => 'required|string|max:200',
            'short_description' => 'nullable|string|max:500',
            'description'       => 'nullable|string',
            'price'             => 'required|numeric|min:0',
            'sale_price'        => 'nullable|numeric|min:0',
            'stock'             => 'required|integer|min:0',
            'sku'               => 'nullable|string|max:100',
            'is_active'               => 'boolean',
            'is_featured'             => 'boolean',
            'is_new'                  => 'boolean',
            'is_on_sale'              => 'boolean',
            'show_when_out_of_stock'  => 'boolean',
            'meta_title'        => 'nullable|string|max:160',
            'meta_description'  => 'nullable|string|max:320',
            'meta_keywords'     => 'nullable|string|max:200',
            'sort_order'        => 'integer',
        ]);
    }

    private function handleImages(Request $request, array $data, ?Product $product = null): array
    {
        // Handle variants from JSON input
        if ($request->filled('variants_json')) {
            try {
                $data['variants'] = json_decode($request->input('variants_json'), true) ?: [];
            } catch (\Exception $e) {
                $data['variants'] = [];
            }
        }

        if ($request->hasFile('main_image')) {
            if ($product?->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }
            $data['main_image'] = $request->file('main_image')->store('products', 'public');
        }

        if ($request->hasFile('gallery') || $request->has('gallery_keep')) {
            // Start with kept existing images (in their new order)
            $kept = [];
            if ($request->filled('gallery_keep')) {
                try { $kept = json_decode($request->input('gallery_keep'), true) ?: []; } catch (\Exception $e) {}
            }

            // Delete removed images from storage
            if ($product?->gallery) {
                foreach ($product->gallery as $oldImg) {
                    if (!in_array($oldImg, $kept)) {
                        Storage::disk('public')->delete($oldImg);
                    }
                }
            }

            // Append newly uploaded images
            $newImages = [];
            if ($request->hasFile('gallery')) {
                foreach ($request->file('gallery') as $file) {
                    $newImages[] = $file->store('products/gallery', 'public');
                }
            }

            $data['gallery'] = array_merge($kept, $newImages);
        }

        // Handle checkboxes (unchecked = not sent in request)
        foreach (['is_active', 'is_featured', 'is_new', 'is_on_sale', 'show_when_out_of_stock'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        return $data;
    }
}