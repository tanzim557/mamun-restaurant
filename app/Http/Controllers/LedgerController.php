<?php

namespace App\Http\Controllers;

use App\Models\DailyLedger;
use Illuminate\Http\Request;

class LedgerController extends Controller
{
    public function index()
    {
        return response()->json(DailyLedger::orderBy('date', 'desc')->get());
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $dateStr = $data['date'] ?? now()->toDateString();

        $entry = DailyLedger::where('date', $dateStr)->first();

        if ($entry) {
            $entry->total_sales = floatval($data['totalSales'] ?? $entry->total_sales);
            $entry->customer_paid = floatval($data['customerPaid'] ?? $entry->customer_paid);
            $entry->market_expense = floatval($data['marketExpense'] ?? $entry->market_expense);
            $entry->salary_paid = floatval($data['salaryPaid'] ?? $entry->salary_paid);
            $entry->personal_expense = floatval($data['personalExpense'] ?? $entry->personal_expense);
            $entry->customer_due_given = floatval($data['customerDueGiven'] ?? $entry->customer_due_given);
            $entry->shomiti_expense = floatval($data['shomitiExpense'] ?? $entry->shomiti_expense);
            $entry->note = $data['note'] ?? $entry->note;
            $entry->save();
        } else {
            $entry = DailyLedger::create([
                'date' => $dateStr,
                'total_sales' => floatval($data['totalSales'] ?? 0),
                'customer_paid' => floatval($data['customerPaid'] ?? 0),
                'market_expense' => floatval($data['marketExpense'] ?? 0),
                'salary_paid' => floatval($data['salaryPaid'] ?? 0),
                'personal_expense' => floatval($data['personalExpense'] ?? 0),
                'customer_due_given' => floatval($data['customerDueGiven'] ?? 0),
                'shomiti_expense' => floatval($data['shomitiExpense'] ?? 0),
                'note' => $data['note'] ?? '',
            ]);
        }

        return response()->json($entry, 200);
    }

    public function destroy($id)
    {
        $entry = DailyLedger::find($id);
        if (!$entry) return response()->json(['error' => 'Not found'], 404);
        $entry->delete();
        return response()->json(['message' => 'Deleted']);
    }

    public function summary()
    {
        $entries = DailyLedger::all();
        $totalIncome = $entries->sum(fn($e) => $e->total_income);
        $totalExpense = $entries->sum(fn($e) => $e->total_expense);
        return response()->json([
            'totalIncome' => $totalIncome,
            'totalExpense' => $totalExpense,
            'netProfit' => $totalIncome - $totalExpense,
            'totalDays' => $entries->count(),
        ]);
    }
}
