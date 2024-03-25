<?php

namespace App\Http\Controllers;

use App\Http\Resources\OrderResource;
use App\Http\Resources\OrderResourceCollection;
use App\Models\Order;
use App\Services\OrderService;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected OrderService $orderService;
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return new OrderResourceCollection(Order::all());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'items' => 'required|array'
        ]);

        try {
            $order = $this->orderService->processOrder($request->user()->id, $validated['items']);
            return new OrderResource($order);
        } catch (\Exception $e) {
            return response()->json(['message' => $e->getMessage()], 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
