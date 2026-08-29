<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MenuItem extends Model
{
    protected $table = 'menu_item';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['name', 'description', 'price', 'image', 'is_available', 'is_featured', 'category_id'];

    protected $casts = [
        'price' => 'float',
        'is_available' => 'boolean',
        'is_featured' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->id) $model->id = (string) Str::uuid();
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function toArray()
    {
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'price' => $this->price,
            'image' => $this->image,
            'isAvailable' => $this->is_available,
            'isFeatured' => $this->is_featured,
            'categoryId' => $this->category_id,
            'createdAt' => $this->created_at?->toISOString(),
            'updatedAt' => $this->updated_at?->toISOString(),
        ];

        if ($this->relationLoaded('category') && $this->category) {
            $data['category'] = [
                'id' => $this->category->id,
                'name' => $this->category->name,
                'slug' => $this->category->slug,
            ];
        }

        return $data;
    }
}
