<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class CustomerDue extends Model
{
    protected $table = 'customer_due';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'phone', 'address', 'total_due', 'paid_amount', 'note'];

    protected $casts = ['total_due' => 'float', 'paid_amount' => 'float'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function toArray()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'address' => $this->address,
            'totalDue' => $this->total_due,
            'paidAmount' => $this->paid_amount,
            'note' => $this->note,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
