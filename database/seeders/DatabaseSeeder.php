<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $userIds = User::factory()->create([
            'name' => 'Test User',
            'email' => 'test@example.com',
        ])->pluck('id');
        User::factory(10)->create();

        $itemIds = Item::factory()->count(50)->create()->pluck('id');
        Order::factory()->count(20)->make()->each(function ($order) use ($userIds) {
            $order->user_id = $userIds->random();
            $order->save();
        });

        Order::all()->each(function ($order) use ($itemIds) {
            $selectedItemIds = $itemIds->random(rand(1, 10))->all();
            foreach ($selectedItemIds as $selectedItemId) {
                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->item_id = $selectedItemId;
                $orderItem->item_quantity = rand(1, 3);
                $orderItem->save();
                // $order->items()->attach($selectedItemId, ['item_quantity' => rand(1, 3)]);
            }
        });
    }
}
