<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class OrderStatusTest extends TestCase
{
    use RefreshDatabase;

    public function testCompletingOrderDeductsStockOnlyOnce()
    {
        $categoryId = DB::table('categories')->insertGetId([
            'name' => 'Test Category',
            'slug' => 'test-category',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $productId = DB::table('products')->insertGetId([
            'category_id' => $categoryId,
            'name' => 'Test Product',
            'price' => 100,
            'discount_percent' => 0,
            'stock' => 10,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderId = DB::table('orders')->insertGetId([
            'customer_name' => 'Customer',
            'customer_phone' => '0770000000',
            'delivery_address' => 'Address',
            'subtotal' => 300,
            'discount_total' => 0,
            'grand_total' => 300,
            'status' => 'ready',
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('order_items')->insert([
            'order_id' => $orderId,
            'product_id' => $productId,
            'product_name' => 'Test Product',
            'quantity' => 3,
            'unit_price' => 100,
            'selling_price' => 100,
            'line_total' => 300,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $session = ['user' => ['role' => 'super_admin']];

        $this->withSession($session)
            ->putJson("/api/orders/{$orderId}/status", ['status' => 'complete'])
            ->assertOk();

        $this->assertEquals(7, DB::table('products')->where('id', $productId)->value('stock'));
        $this->assertEquals(1, DB::table('orders')->where('id', $orderId)->value('stock_update'));

        $this->withSession($session)
            ->putJson("/api/orders/{$orderId}/status", ['status' => 'complete'])
            ->assertOk();

        $this->assertEquals(7, DB::table('products')->where('id', $productId)->value('stock'));
    }
}
