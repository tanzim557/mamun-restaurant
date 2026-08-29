<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Reservation;
use App\Models\MenuItem;
use App\Models\Category;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function manageOrder(Request $request, $id)
    {
        $order = Order::find($id);
        if (!$order) return response()->json(['error' => 'Order not found'], 404);

        if ($request->isMethod('patch')) {
            if ($request->has('status')) $order->status = $request->input('status');
            $order->save();
            return response()->json($order);
        }

        if ($request->isMethod('delete')) {
            $order->delete();
            return response()->json(['message' => 'Order deleted']);
        }
    }

    public function reservations()
    {
        $reservations = Reservation::orderBy('created_at', 'desc')->get();
        return response()->json($reservations);
    }

    public function manageReservation(Request $request, $id)
    {
        $reservation = Reservation::find($id);
        if (!$reservation) return response()->json(['error' => 'Reservation not found'], 404);

        if ($request->isMethod('patch')) {
            if ($request->has('status')) $reservation->status = $request->input('status');
            $reservation->save();
            return response()->json($reservation);
        }

        if ($request->isMethod('delete')) {
            $reservation->delete();
            return response()->json(['message' => 'Reservation deleted']);
        }
    }

    public function createMenuItem(Request $request)
    {
        $data = $request->all();
        $categoryId = $data['categoryId'] ?? Category::first()?->id;

        $item = MenuItem::create([
            'name' => $data['name'] ?? '',
            'description' => $data['description'] ?? '',
            'price' => floatval($data['price'] ?? 0),
            'image' => $data['image'] ?? null,
            'is_featured' => $data['isFeatured'] ?? false,
            'category_id' => $categoryId,
        ]);

        $item->load('category');
        return response()->json($item, 201);
    }

    public function manageMenuItem(Request $request, $id)
    {
        $item = MenuItem::find($id);
        if (!$item) return response()->json(['error' => 'Item not found'], 404);

        if ($request->isMethod('patch')) {
            $data = $request->all();
            if (isset($data['name'])) $item->name = $data['name'];
            if (isset($data['description'])) $item->description = $data['description'];
            if (isset($data['price'])) $item->price = floatval($data['price']);
            if (isset($data['image'])) $item->image = $data['image'];
            if (isset($data['isFeatured'])) $item->is_featured = $data['isFeatured'];
            if (isset($data['isAvailable'])) $item->is_available = $data['isAvailable'];
            $item->save();
            $item->load('category');
            return response()->json($item);
        }

        if ($request->isMethod('delete')) {
            $item->delete();
            return response()->json(['message' => 'Item deleted']);
        }
    }
}
