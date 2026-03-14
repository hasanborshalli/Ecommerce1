<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactMessage;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;

class AdminDashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_orders'    => Order::count(),
            'pending_orders'  => Order::where('status', 'pending')->count(),
            'total_products'  => Product::count(),
            'total_revenue'   => Order::whereIn('status', ['confirmed','processing','shipped','delivered'])->sum('total'),
            'unread_messages' => ContactMessage::where('is_read', false)->count(),
            'total_categories'=> Category::count(),
        ];

        $recentOrders = Order::with('items')
            ->orderBy('created_at', 'desc')
            ->take(8)
            ->get();

        $topProducts = Product::withCount('orderItems')
            ->orderBy('order_items_count', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentOrders', 'topProducts'));
    }
}