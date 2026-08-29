<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class OrderItem extends Model
{
    protected $table = 'order_item';
    protected $keyType = 'string';
    public $incrementing = false;
    public $timestamps = false;

    protected $fillable = ['order_id', 'menu_item_name', 'quantity', 'price'];

    protected $casts = ['quantity' => 'integer', 'price' => 'float'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'orderId' => $this->order_id,
            'name' => $this->menu_item_name,
            'quantity' => $this->quantity,
            'price' => $this->price,
        ];
    }
}
