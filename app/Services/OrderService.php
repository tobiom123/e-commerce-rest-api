<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Order;
use App\Models\OrderItem;
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
                $itemId = $itemData['id'];
                $itemQuantity = $itemData['item_quantity'];
                $item = Item::findOrFail($itemId);

                if ($item->stock < $itemQuantity) {
                    throw new \Exception("Insufficient stock for item {$item->name}");
                }

                $orderItem = new OrderItem();
                $orderItem->order_id = $order->id;
                $orderItem->item_id = $itemId;
                $orderItem->item_quantity = $itemQuantity;
                $orderItem->save();
                $total += $item->price * $itemQuantity;
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
