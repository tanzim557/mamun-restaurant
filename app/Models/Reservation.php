<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Reservation extends Model
{
    protected $table = 'reservation';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['customer_name', 'phone_number', 'email', 'date', 'time', 'guests', 'special_request', 'status'];

    protected $casts = ['guests' => 'integer'];

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
            'customerName' => $this->customer_name,
            'phoneNumber' => $this->phone_number,
            'email' => $this->email,
            'date' => $this->date,
            'time' => $this->time,
            'guests' => $this->guests,
            'specialRequest' => $this->special_request,
            'status' => $this->status,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
