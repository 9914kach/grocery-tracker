<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PriceRecord;
use App\Models\Store;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class OrderImportService
{
    public function import(User $user, array $data): array
    {
        $hash = $this->computeHash($user->id, $data);

        if ($existing = Order::where('import_hash', $hash)->first()) {
            return [
                'order_id' => $existing->id,
                'status'   => 'already_imported',
            ];
        }

        return DB::transaction(function () use ($user, $data, $hash) {
            $store = Store::firstOrCreate(
                ['chain' => strtolower($data['store'])],
                ['name'  => $data['store']],
            );

            $order = Order::create([
                'user_id'           => $user->id,
                'store_id'          => $store->id,
                'ordered_at'        => $data['ordered_at'],
                'receipt_reference' => $data['receipt_reference'] ?? null,
                'import_hash'       => $hash,
            ]);

            foreach ($data['items'] as $item) {
                $storeProduct = $this->resolveStoreProduct($store->id, $item);

                PriceRecord::create([
                    'store_product_id' => $storeProduct->id,
                    'price'            => $item['unit_price'],
                    'currency'         => 'SEK',
                    'recorded_at'      => $data['ordered_at'],
                ]);

                OrderItem::create([
                    'order_id'         => $order->id,
                    'store_product_id' => $storeProduct->id,
                    'quantity'         => $item['quantity'],
                    'unit_price'       => $item['unit_price'],
                    'total_price'      => $item['line_total'] ?? $item['quantity'] * $item['unit_price'],
                ]);
            }

            return [
                'order_id'    => $order->id,
                'status'      => 'created',
                'items_count' => count($data['items']),
            ];
        });
    }

    private function resolveStoreProduct(int $storeId, array $item): StoreProduct
    {
        if (!empty($item['external_reference'])) {
            return StoreProduct::firstOrCreate(
                ['store_id' => $storeId, 'external_id' => $item['external_reference']],
                [
                    'name'      => $item['raw_name'],
                    'unit_size' => isset($item['weight_grams']) ? $item['weight_grams'].'g' : null,
                ],
            );
        }

        return StoreProduct::firstOrCreate(
            ['store_id' => $storeId, 'name' => $item['raw_name']],
            [
                'unit_size' => isset($item['weight_grams']) ? $item['weight_grams'].'g' : null,
            ],
        );
    }

    private function computeHash(int $userId, array $data): string
    {
        $items = collect($data['items'])
            ->map(fn($item) => $item['raw_name'].':'.$item['unit_price'])
            ->sort()
            ->values()
            ->implode('|');

        $raw = implode('.', [
            $userId,
            strtolower($data['store']),
            date('Y-m-d', strtotime($data['ordered_at'])),
            $items,
        ]);

        return hash('sha256', $raw);
    }
}
