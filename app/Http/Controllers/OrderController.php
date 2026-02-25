<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Inertia\Inertia;
use Inertia\Response;

class OrderController extends Controller
{
    public function index(): Response
    {
        $orders = Order::with('store')
            ->where('user_id', auth()->id())
            ->withCount('items')
            ->latest('ordered_at')
            ->paginate(20)
            ->through(fn($order) => [
                'id'                => $order->id,
                'ordered_at'        => $order->ordered_at->format('Y-m-d'),
                'store'             => $order->store->name,
                'items_count'       => $order->items_count,
                'receipt_reference' => $order->receipt_reference,
            ]);

        return Inertia::render('Orders/Index', [
            'orders' => $orders,
        ]);
    }
}
