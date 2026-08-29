<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with('orderItems')->orderBy('created_at', 'desc')->get();
        return response()->json($orders);
    }

    public function store(Request $request)
    {
        try {
            $data = $request->all();

            $order = Order::create([
                'customer_name' => $data['customerName'] ?? '',
                'phone_number' => $data['phoneNumber'] ?? '',
                'address' => $data['address'] ?? '',
                'total_amount' => 0,
                'note' => $data['note'] ?? '',
            ]);

            $items = $data['items'] ?? [];
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_name' => $item['name'] ?? '',
                    'quantity' => $item['qty'] ?? $item['quantity'] ?? 1,
                    'price' => $item['price'] ?? 0,
                ]);
            }

            // Refresh to get trigger-updated total_amount
            $order->refresh();
            $order->load('orderItems');

            return response()->json(['success' => true, 'order' => $order], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
