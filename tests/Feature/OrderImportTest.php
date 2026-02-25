<?php

namespace Tests\Feature;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\PriceRecord;
use App\Models\StoreProduct;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class OrderImportTest extends TestCase
{
    use RefreshDatabase;

    private function payload(array $overrides = []): array
    {
        return array_merge([
            'store'               => 'ica',
            'ordered_at'          => '2024-01-15T10:30:00',
            'receipt_reference'   => 'REC-001',
            'items'               => [
                [
                    'raw_name'           => 'Mjölk 3% 1L',
                    'quantity'           => 2,
                    'unit_price'         => 13.90,
                    'line_total'         => 27.80,
                    'external_reference' => '7310865004703',
                    'weight_grams'       => null,
                ],
                [
                    'raw_name'           => 'Ägg 12-pack',
                    'quantity'           => 1,
                    'unit_price'         => 39.90,
                    'line_total'         => 39.90,
                    'external_reference' => '7311041010878',
                    'weight_grams'       => null,
                ],
            ],
        ], $overrides);
    }

    public function test_it_creates_order_with_items(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)
            ->postJson('/api/orders/import', $this->payload());

        $response->assertStatus(201)
            ->assertJsonStructure(['order_id', 'status', 'items_count'])
            ->assertJsonFragment(['status' => 'created', 'items_count' => 2]);

        $this->assertDatabaseCount('orders', 1);
        $this->assertDatabaseCount('order_items', 2);
        $this->assertDatabaseCount('store_products', 2);
        $this->assertDatabaseCount('price_records', 2);

        $this->assertDatabaseHas('orders', [
            'receipt_reference' => 'REC-001',
        ]);
    }

    public function test_it_creates_store_product_when_missing(): void
    {
        $user = User::factory()->create();

        $payload = $this->payload([
            'items' => [
                [
                    'raw_name'           => 'Havregrynspaket 500g',
                    'quantity'           => 1,
                    'unit_price'         => 19.90,
                    'line_total'         => 19.90,
                    'external_reference' => null,
                    'weight_grams'       => 500,
                ],
            ],
        ]);

        $this->actingAs($user)->postJson('/api/orders/import', $payload)->assertStatus(201);

        $this->assertDatabaseHas('store_products', [
            'name'      => 'Havregrynspaket 500g',
            'unit_size' => '500g',
        ]);

        $this->assertDatabaseCount('price_records', 1);
        $this->assertDatabaseCount('order_items', 1);
    }

    public function test_it_is_idempotent(): void
    {
        $user = User::factory()->create();
        $payload = $this->payload();

        $first = $this->actingAs($user)->postJson('/api/orders/import', $payload);
        $first->assertStatus(201)->assertJsonFragment(['status' => 'created']);

        $second = $this->actingAs($user)->postJson('/api/orders/import', $payload);
        $second->assertStatus(200)->assertJsonFragment(['status' => 'already_imported']);

        $this->assertDatabaseCount('orders', 1);
        $this->assertSame($first->json('order_id'), $second->json('order_id'));
    }
}
