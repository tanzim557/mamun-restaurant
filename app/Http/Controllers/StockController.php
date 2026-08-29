<?php

namespace App\Http\Controllers;

use App\Models\StockItem;
use Illuminate\Http\Request;

class StockController extends Controller
{
    public function index()
    {
        return response()->json(StockItem::orderBy('name', 'asc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!($data['name'] ?? null) || !($data['unit'] ?? null)) {
            return response()->json(['error' => 'Name and unit are required'], 400);
        }

        $item = StockItem::create([
            'name' => $data['name'],
            'quantity' => floatval($data['quantity'] ?? 0),
            'unit' => $data['unit'],
            'min_quantity' => floatval($data['minQuantity'] ?? 5),
            'last_price' => floatval($data['lastPrice'] ?? 0),
        ]);
        return response()->json($item, 201);
    }

    public function update(Request $request, $id)
    {
        $item = StockItem::find($id);
        if (!$item) return response()->json(['error' => 'Not found'], 404);

        $data = $request->all();
        if (isset($data['name'])) $item->name = $data['name'];
        if (isset($data['quantity'])) $item->quantity = floatval($data['quantity']);
        if (isset($data['unit'])) $item->unit = $data['unit'];
        if (isset($data['minQuantity'])) $item->min_quantity = floatval($data['minQuantity']);
        if (isset($data['lastPrice'])) $item->last_price = floatval($data['lastPrice']);
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
