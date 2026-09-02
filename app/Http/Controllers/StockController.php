<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        $items = StockItem::orderBy('name', 'asc')->get();
        $lowStock = $items->filter(fn($i) => $i->quantity <= $i->min_quantity)->values();

        return response()->json([
            'items' => $items,
            'totalCount' => $items->count(),
            'lowStockCount' => $lowStock->count(),
            'lowStockItems' => $lowStock,
        ]);
    }

    public function rawItems()
    {
        return response()->json(StockItem::orderBy('name', 'asc')->get());
    }

    public function alerts()
    {
        $lowStock = StockItem::whereColumn('quantity', '<=', 'min_quantity')->orderBy('quantity', 'asc')->get();
        return response()->json([
            'count' => $lowStock->count(),
            'hasAlert' => $lowStock->count() > 0,
            'items' => $lowStock
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!($data['name'] ?? null) || !($data['unit'] ?? null)) {
            return response()->json(['error' => 'নাম এবং ইউনিট আবশ্যক'], 400);
        }

        $item = StockItem::create([
            'name' => $data['name'],
            'category' => $data['category'] ?? 'কাঁচামাল',
            'quantity' => floatval($data['quantity'] ?? ($data['currentStock'] ?? 0)),
            'used_quantity' => floatval($data['usedQuantity'] ?? ($data['used_quantity'] ?? 0)),
            'unit' => $data['unit'],
            'min_quantity' => floatval($data['minQuantity'] ?? ($data['min_quantity'] ?? 5)),
            'last_price' => floatval($data['lastPrice'] ?? ($data['last_price'] ?? 0)),
        ]);

        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $item = StockItem::find($id);
        if (!$item) return response()->json(['error' => 'Not found'], 404);

        $data = $request->all();

        // 1. Quick Action: Add Stock
        if (($data['action'] ?? null) === 'add_stock') {
            $amount = floatval($data['amount'] ?? 0);
            $item->quantity += $amount;
            if (isset($data['lastPrice'])) $item->last_price = floatval($data['lastPrice']);
            $item->save();
            return response()->json($item);
        }

        // 2. Quick Action: Use / Consume Stock
        if (($data['action'] ?? null) === 'use_stock') {
            $amount = floatval($data['amount'] ?? 0);
            $item->quantity = max(0, $item->quantity - $amount);
            $item->used_quantity += $amount;
            $item->save();
            return response()->json($item);
        }

        // 3. Full Update
        if (isset($data['name'])) $item->name = $data['name'];
        if (isset($data['category'])) $item->category = $data['category'];
        if (isset($data['quantity'])) $item->quantity = floatval($data['quantity']);
        if (isset($data['currentStock'])) $item->quantity = floatval($data['currentStock']);
        if (isset($data['usedQuantity'])) $item->used_quantity = floatval($data['usedQuantity']);
        if (isset($data['used_quantity'])) $item->used_quantity = floatval($data['used_quantity']);
        if (isset($data['unit'])) $item->unit = $data['unit'];
        if (isset($data['minQuantity'])) $item->min_quantity = floatval($data['minQuantity']);
        if (isset($data['min_quantity'])) $item->min_quantity = floatval($data['min_quantity']);
        if (isset($data['lastPrice'])) $item->last_price = floatval($data['lastPrice']);
        if (isset($data['last_price'])) $item->last_price = floatval($data['last_price']);

        $item->save();
        return response()->json($item);
    }

    public function destroy($id)
    {
        $item = StockItem::find($id);
        if (!$item) return response()->json(['error' => 'Not found'], 404);
        $item->delete();
        return response()->json(['message' => 'Deleted']);
    }
}
