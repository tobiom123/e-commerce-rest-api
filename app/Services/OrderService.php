<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Order;
use Illuminate\Support\Facades\DB;

class OrderService
{
    public function processOrder(int $userId, array $items): Order
    {
        DB::beginTransaction();

        try {
            $order = new Order();
            $order->user_id = $userId;
            $order->status = 'pending';
            $order->save();

            $total = 0;
            foreach ($items as $itemData) {
                $item = Item::findOrFail($itemData['id']);

                if ($item->stock < $itemData['quantity']) {
                    throw new \Exception("Insufficient stock for item {$item->name}");
                }

                $order->items()->attach($item->id, ['quantity' => $itemData['quantity']]);
                $item->decrement('stock', $itemData['quantity']);
                $total += $item->price * $itemData['quantity'];
            }

            $order->total = $total;
            $order->status = $this->simulatePayment() ? 'completed' : 'failed';
            $order->save();

            DB::commit();
            return $order;
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function simulatePayment(): bool
    {
        return rand(0, 1) == 1;
    }
}
