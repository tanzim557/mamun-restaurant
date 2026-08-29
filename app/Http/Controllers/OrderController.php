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
        
        // Auto-fix any older orders where total_amount was saved as 0
        foreach ($orders as $order) {
            if ($order->total_amount <= 0 && $order->orderItems->count() > 0) {
                $calcTotal = $order->orderItems->sum(function($item) {
                    return ($item->price ?? 0) * ($item->quantity ?? 1);
                });
                if ($calcTotal > 0) {
                    $order->update(['total_amount' => $calcTotal]);
                    $order->total_amount = $calcTotal;
                }
            }
        }

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
                'status' => 'PENDING',
                'note' => $data['note'] ?? '',
            ]);

            $total = 0;
            $items = $data['items'] ?? [];
            foreach ($items as $item) {
                $qty = (int)($item['qty'] ?? $item['quantity'] ?? 1);
                $price = (float)($item['price'] ?? 0);
                $total += ($qty * $price);

                OrderItem::create([
                    'order_id' => $order->id,
                    'menu_item_name' => $item['name'] ?? '',
                    'quantity' => $qty,
                    'price' => $price,
                ]);
            }

            // Save the exact calculated total amount
            $order->update(['total_amount' => $total]);
            $order->refresh();
            $order->load('orderItems');

            $shortId = 'MR-' . strtoupper(substr(str_replace('-', '', $order->id), 0, 6));

            return response()->json([
                'success' => true,
                'order' => array_merge($order->toArray(), ['shortId' => $shortId])
            ], 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function track(Request $request)
    {
        try {
            $query = trim($request->input('query', ''));
            $phone = trim($request->input('phone', ''));
            $orderId = trim($request->input('orderId', ''));

            $search = $query ?: ($orderId ?: $phone);
            if (!$search) {
                return response()->json(['error' => 'অর্ডার নম্বর বা মোবাইল নম্বর প্রদান করুন।'], 400);
            }

            $cleanSearch = str_ireplace(['#', 'MR-', 'MR', ' '], '', $search);

            $order = Order::with('orderItems')
                ->where('id', $search)
                ->orWhere('id', 'LIKE', '%' . $cleanSearch . '%')
                ->orWhere('phone_number', 'LIKE', '%' . $search . '%')
                ->orderBy('created_at', 'desc')
                ->first();

            if (!$order) {
                return response()->json(['error' => 'কোনো অর্ডার পাওয়া যায়নি। সঠিক অর্ডার আইডি বা মোবাইল নম্বর দিন।'], 404);
            }

            $shortId = 'MR-' . strtoupper(substr(str_replace('-', '', $order->id), 0, 6));

            return response()->json([
                'success' => true,
                'order' => array_merge($order->toArray(), ['shortId' => $shortId])
            ]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
