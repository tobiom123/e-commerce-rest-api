<?php

namespace Tests\Unit\Http\Controllers;

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
        $number = 8;
        $orderServiceMock = Mock(OrderService::class, function (MockInterface $mock) use ($number) {
            $mock->shouldReceive('processOrder')
                ->once();
            //->with($number)->andReturn('VIII');
        });
        $this->app->instance(OrderService::class, $orderServiceMock);
        $this->post('/api/orders', ['items' => [
            ['id' => 1, 'item_quantity' => 2],
            ['id' => 2, 'item_quantity' => 2],
        ]]);
    }

}
