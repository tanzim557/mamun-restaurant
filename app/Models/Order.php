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
        $total = (float)$this->total_amount;
        if ($total <= 0 && $this->relationLoaded('orderItems')) {
            $total = (float)$this->orderItems->sum(fn($i) => ($i->price ?? 0) * ($i->quantity ?? 1));
        }

        return [
            'id' => $this->id,
            'shortId' => 'MR-' . strtoupper(substr(str_replace('-', '', $this->id), 0, 6)),
            'customerName' => $this->customer_name,
            'phoneNumber' => $this->phone_number,
            'address' => $this->address,
            'items' => $this->relationLoaded('orderItems') ? $this->orderItems->toArray() : [],
            'totalAmount' => $total,
            'status' => $this->status ?: 'PENDING',
            'note' => $this->note,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
