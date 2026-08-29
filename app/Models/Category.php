<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $table = 'category';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'slug'];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function menuItems()
    {
        return $this->hasMany(MenuItem::class, 'category_id');
    }

    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'slug' => $this->slug,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];

        if ($this->relationLoaded('menuItems')) {
            $data['menuItems'] = $this->menuItems->toArray();
        }

        return $data;
    }
}
