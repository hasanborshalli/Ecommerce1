<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\SiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    private function getCart(): array
    {
        return session('cart', []);
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

        return compact('subtotal', 'shipping', 'total');
    }

    public function index()
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $totals = $this->cartTotals($cart);
        $meta   = ['title' => 'Checkout — ' . SiteSetting::get('site_name')];

        return view('checkout', compact('cart', 'totals', 'meta'));
    }

    public function place(Request $request)
    {
        $cart = $this->getCart();

        if (empty($cart)) {
            return redirect()->route('cart.index')->with('error', 'Your cart is empty.');
        }

        $request->validate([
            'customer_name'    => 'required|string|max:120',
            'customer_email'   => 'required|email|max:120',
            'customer_phone'   => 'nullable|string|max:30',
            'shipping_address' => 'required|string|max:255',
            'shipping_city'    => 'required|string|max:100',
            'notes'            => 'nullable|string|max:500',
        ]);

        $totals = $this->cartTotals($cart);

        DB::transaction(function () use ($request, $cart, $totals, &$order) {
            $order = Order::create([
                'order_number'     => Order::generateOrderNumber(),
                'customer_name'    => $request->customer_name,
                'customer_email'   => $request->customer_email,
                'customer_phone'   => $request->customer_phone,
                'shipping_address' => $request->shipping_address,
                'shipping_city'    => $request->shipping_city,
                'shipping_state'   => null,
                'shipping_zip'     => null,
                'shipping_country' => null,
                'subtotal'         => $totals['subtotal'],
                'shipping_cost'    => $totals['shipping'],
                'discount'         => 0,
                'total'            => $totals['total'],
                'payment_method'   => 'cash_on_delivery',
                'notes'            => $request->notes,
                'status'           => 'pending',
                'payment_status'   => 'unpaid',
            ]);

            foreach ($cart as $item) {
                OrderItem::create([
                    'order_id'      => $order->id,
                    'product_id'    => $item['id'],
                    'product_name'  => $item['name'],
                    'product_price' => $item['price'],
                    'quantity'      => $item['quantity'],
                    'variant'       => $item['variant'] ?? [],
                    'line_total'    => $item['price'] * $item['quantity'],
                ]);

                // Decrement stock
                Product::where('id', $item['id'])->decrement('stock', $item['quantity']);
            }
        });

        // Clear cart
        session()->forget('cart');

        return redirect()->route('order.confirmation', $order->order_number);
    }

    public function confirmation(string $orderNumber)
    {
        $order = Order::where('order_number', $orderNumber)
            ->with('items')
            ->firstOrFail();

        $meta = [
            'title' => 'Order Confirmed — ' . SiteSetting::get('site_name'),
        ];

        return view('order-confirmation', compact('order', 'meta'));
    }
}