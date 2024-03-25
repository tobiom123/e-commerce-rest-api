<?php

namespace Tests\Unit\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use App\Services\OrderService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Mockery\MockInterface;
// use PHPUnit\Framework\TestCase;
use Tests\TestCase;

class OrderControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_store_method_calls_order_service_to_process_order_and_simulate_payments()
    {
        $user = User::factory()->create();
        $items = [
            ['id' => 1, 'item_quantity' => 2],
            ['id' => 2, 'item_quantity' => 2],
        ];
        $orderServiceMock = Mock(OrderService::class, function (MockInterface $mock) use ($user, $items) {
            $mock->shouldReceive('processOrder')->once()->with($user->id, $items)->andReturn(new Order());
        });
        $this->app->instance(OrderService::class, $orderServiceMock);
        $response = $this->actingAs($user)->post('/api/orders', ['items' => $items]);

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'id',
                    'user_id',
                    'total',
                    'status',
                    'created_at',
                    'updated_at'
                ],
            ],
        ]);
    }

}
