<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;

class ReservationController extends Controller
{
    public function store(Request $request)
    {
        try {
            $data = $request->all();
            $reservation = Reservation::create([
                'customer_name' => $data['customerName'] ?? $data['name'] ?? '',
                'phone_number' => $data['phoneNumber'] ?? $data['phone'] ?? '',
                'email' => $data['email'] ?? null,
                'date' => $data['date'] ?? now()->toDateString(),
                'time' => $data['time'] ?? '',
                'guests' => intval($data['guests'] ?? 1),
                'special_request' => $data['specialRequest'] ?? $data['message'] ?? '',
            ]);
            return response()->json($reservation, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
