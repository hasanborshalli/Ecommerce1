<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class CartController extends Controller
{
    // ── Helpers ────────────────────────────────────────────────────────

    private function getCart(): array
    {
        return session('cart', []);
    }

    private function saveCart(array $cart): void
    {
        session(['cart' => $cart]);
    }

    private function cartTotals(array $cart): array
    {
        $subtotal     = 0;
        $freeOver     = (float) SiteSetting::get('free_shipping_over', 100);
        $shippingCost = (float) SiteSetting::get('shipping_cost', 8);

        foreach ($cart as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $shipping = $subtotal >= $freeOver ? 0 : $shippingCost;
        $total    = $subtotal + $shipping;

        return compact('subtotal', 'shipping', 'total', 'freeOver');
    }

    // ── Actions ─────────────────────────────────────────────────────────

    public function index()
    {
        $cart   = $this->getCart();
        $totals = $this->cartTotals($cart);
        $meta   = ['title' => 'Cart — ' . SiteSetting::get('site_name')];

        return view('cart', compact('cart', 'totals', 'meta'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity'   => 'integer|min:1|max:99',
            'variant'    => 'nullable|array',
        ]);

        $product = Product::where('is_active', true)->where('stock', '>', 0)->findOrFail($request->product_id);
        $qty     = (int) $request->get('quantity', 1);
        $variant = $request->get('variant', []);

        // Build a unique row key based on product + variant combo
        $rowId = md5($product->id . serialize($variant));

        $cart = $this->getCart();

        if (isset($cart[$rowId])) {
            $cart[$rowId]['quantity'] = min($cart[$rowId]['quantity'] + $qty, 99);
        } else {
            $cart[$rowId] = [
                'row_id'    => $rowId,
                'id'        => $product->id,
                'name'      => $product->name,
                'slug'      => $product->slug,
                'price'     => $product->effective_price,
                'image'     => $product->main_image,
                'quantity'  => $qty,
                'variant'   => $variant,
            ];
        }

        $this->saveCart($cart);

        if ($request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => 'Item added to cart',
                'count'   => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return back()->with('success', 'Item added to cart');
    }

    public function update(Request $request)
    {
        $request->validate([
            'row_id'   => 'required|string',
            'quantity' => 'required|integer|min:0|max:99',
        ]);

        $cart  = $this->getCart();
        $rowId = $request->row_id;

        if (isset($cart[$rowId])) {
            if ($request->quantity == 0) {
                unset($cart[$rowId]);
            } else {
                $cart[$rowId]['quantity'] = $request->quantity;
            }
        }

        $this->saveCart($cart);

        if ($request->wantsJson()) {
            $totals = $this->cartTotals($cart);
            return response()->json([
                'success' => true,
                'totals'  => $totals,
                'count'   => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index');
    }

    public function remove(Request $request, string $rowId)
    {
        $cart = $this->getCart();
        unset($cart[$rowId]);
        $this->saveCart($cart);

        if ($request->wantsJson()) {
            $totals = $this->cartTotals($cart);
            return response()->json([
                'success' => true,
                'totals'  => $totals,
                'count'   => array_sum(array_column($cart, 'quantity')),
            ]);
        }

        return redirect()->route('cart.index')->with('success', 'Item removed');
    }

    public function clear(Request $request)
    {
        $this->saveCart([]);

        if ($request->wantsJson()) {
            return response()->json(['success' => true, 'count' => 0]);
        }

        return redirect()->route('cart.index');
    }

    public function count(Request $request)
    {
        $cart = $this->getCart();
        return response()->json([
            'count' => array_sum(array_column($cart, 'quantity')),
        ]);
    }
}