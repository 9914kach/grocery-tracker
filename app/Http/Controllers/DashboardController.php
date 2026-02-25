<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use App\Models\StoreProduct;
use Inertia\Inertia;
use Inertia\Response;

class DashboardController extends Controller
{
    public function index(): Response
    {
        return Inertia::render('Dashboard', [
            'stats' => [
                'orders_count'       => Order::count(),
                'stores_count'       => Store::count(),
                'products_tracked'   => StoreProduct::count(),
                'needs_review_count' => StoreProduct::whereNull('product_id')->count(),
            ],
            'recent_orders' => Order::with('store')
                ->withCount('items')
                ->latest('ordered_at')
                ->limit(5)
                ->get()
                ->map(fn($order) => [
                    'id'                => $order->id,
                    'ordered_at'        => $order->ordered_at->format('Y-m-d'),
                    'store'             => $order->store->name,
                    'items_count'       => $order->items_count,
                    'receipt_reference' => $order->receipt_reference,
                ]),
        ]);
    }
}
