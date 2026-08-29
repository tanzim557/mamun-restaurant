<?php

namespace App\Http\Controllers;

use App\Models\CustomerDue;
use Illuminate\Http\Request;

class CustomerDueController extends Controller
{
    public function index()
    {
        return response()->json(CustomerDue::orderBy('created_at', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $due = CustomerDue::create([
            'name' => $data['name'] ?? '',
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
            'total_due' => floatval($data['totalDue'] ?? 0),
            'paid_amount' => floatval($data['paidAmount'] ?? 0),
            'note' => $data['note'] ?? null,
        ]);
        return response()->json($due, 201);
    }

    public function update(Request $request, $id)
    {
        $due = CustomerDue::find($id);
        if (!$due) return response()->json(['error' => 'Due not found'], 404);

        $data = $request->all();
        if (isset($data['name'])) $due->name = $data['name'];
        if (isset($data['phone'])) $due->phone = $data['phone'];
        if (isset($data['address'])) $due->address = $data['address'];
        if (isset($data['totalDue'])) $due->total_due = floatval($data['totalDue']);
        if (isset($data['paidAmount'])) $due->paid_amount = floatval($data['paidAmount']);
        if (isset($data['note'])) $due->note = $data['note'];
        $due->save();
        return response()->json($due);
    }

    public function destroy($id)
    {
        $due = CustomerDue::find($id);
        if (!$due) return response()->json(['error' => 'Due not found'], 404);
        $due->delete();
        return response()->json(['message' => 'Due deleted']);
    }
}
