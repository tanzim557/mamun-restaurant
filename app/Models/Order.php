<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Order extends Model
{
    protected $table = 'order';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['customer_name', 'phone_number', 'address', 'total_amount', 'status', 'note'];

    protected $casts = ['total_amount' => 'float'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'customerName' => $this->customer_name,
            'phoneNumber' => $this->phone_number,
            'address' => $this->address,
            'items' => $this->relationLoaded('orderItems') ? $this->orderItems->toArray() : [],
            'totalAmount' => $this->total_amount,
            'status' => $this->status,
            'note' => $this->note,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
