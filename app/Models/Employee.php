<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Employee extends Model
{
    protected $table = 'employee';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'position', 'phone', 'salary', 'salary_due', 'join_date', 'note'];

    protected $casts = ['salary' => 'float', 'salary_due' => 'float'];

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
            'position' => $this->position,
            'phone' => $this->phone,
            'salary' => $this->salary,
            'salaryDue' => $this->salary_due,
            'joinDate' => $this->join_date,
            'note' => $this->note,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];
    }
}
