<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StockItem extends Model
{
    protected $table = 'stock_item';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'quantity', 'unit', 'min_quantity', 'last_price'];

    protected $casts = [
        'quantity' => 'float', 'min_quantity' => 'float', 'last_price' => 'float',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function getIsLowStockAttribute()
    {
        return $this->quantity <= $this->min_quantity;
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'minQuantity' => $this->min_quantity,
            'lastPrice' => $this->last_price,
            'isLowStock' => $this->is_low_stock,
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
