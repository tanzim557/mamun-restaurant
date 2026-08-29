<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class DailyLedger extends Model
{
    protected $table = 'daily_ledger';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = [
        'date', 'total_sales', 'customer_paid', 'market_expense',
        'salary_paid', 'personal_expense', 'customer_due_given',
        'shomiti_expense', 'note'
    ];

    protected $casts = [
        'total_sales' => 'float', 'customer_paid' => 'float',
        'market_expense' => 'float', 'salary_paid' => 'float',
        'personal_expense' => 'float', 'customer_due_given' => 'float',
        'shomiti_expense' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function getTotalIncomeAttribute()
    {
        return $this->total_sales + $this->customer_paid;
    }

    public function getTotalExpenseAttribute()
    {
        return $this->market_expense + $this->salary_paid + $this->personal_expense
             + $this->customer_due_given + $this->shomiti_expense;
    }

    public function getNetProfitAttribute()
    {
        return $this->total_income - $this->total_expense;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'date' => $this->date,
            'totalSales' => $this->total_sales,
            'customerPaid' => $this->customer_paid,
            'marketExpense' => $this->market_expense,
            'salaryPaid' => $this->salary_paid,
            'personalExpense' => $this->personal_expense,
            'customerDueGiven' => $this->customer_due_given,
            'shomitiExpense' => $this->shomiti_expense,
            'totalIncome' => $this->total_income,
            'totalExpense' => $this->total_expense,
            'netProfit' => $this->net_profit,
            'note' => $this->note,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
