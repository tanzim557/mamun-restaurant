<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Admin user
        if (!User::where('email', 'admin@mamun.com')->exists()) {
            User::create([
                'name' => 'Admin',
                'email' => 'admin@mamun.com',
                'password' => 'admin',
                'role' => 'ADMIN',
            ]);
        }

        // Categories & Menu Items
        $categories = [
            'ভাত ও তরকারি' => [
                ['name' => 'চুইঝালের গরু (ফুল)', 'price' => 320, 'description' => 'সাতক্ষীরার বিখ্যাত অরিজিনাল চুইঝাল দিয়ে রান্না করা সুস্বাদু গরুর গোশত।', 'is_featured' => true],
                ['name' => 'চুইঝালের গরু (হাফ)', 'price' => 170, 'description' => 'চুইঝাল দিয়ে তৈরি গরুর গোশত (হাফ প্লেট)।'],
                ['name' => 'কালাভুনা (ফুল)', 'price' => 340, 'description' => 'ঐতিহ্যবাহী মশলায় তৈরি স্পেশাল গরুর কালাভুনা।', 'is_featured' => true],
                ['name' => 'কালাভুনা (হাফ)', 'price' => 180, 'description' => 'গরুর কালাভুনা হাফ প্লেট।'],
                ['name' => 'প্লেইন ভাত', 'price' => 30, 'description' => 'সাদা ভাত।'],
            ],
            'মাছ' => [
                ['name' => 'রুই মাছ ভাজা', 'price' => 120, 'description' => 'তাজা রুই মাছ ভাজা।'],
                ['name' => 'ইলিশ ভাজা', 'price' => 250, 'description' => 'পদ্মার ইলিশ কড়া ভাজা।'],
            ],
            'মুরগি' => [
                ['name' => 'মুরগি ভুনা (ফুল)', 'price' => 280, 'description' => 'দেশি মুরগির ভুনা।'],
                ['name' => 'মুরগি ভুনা (হাফ)', 'price' => 150, 'description' => 'দেশি মুরগির ভুনা (হাফ)।'],
            ],
            'নাস্তা ও পানীয়' => [
                ['name' => 'গরুর নেহারী', 'price' => 200, 'description' => 'সকালের নাস্তায় স্পেশাল গরুর নেহারী।', 'is_featured' => true],
                ['name' => 'চা', 'price' => 15, 'description' => 'গরম চা।'],
            ],
        ];

        foreach ($categories as $catName => $items) {
            $slug = Str::slug($catName);
            $cat = Category::firstOrCreate(
                ['slug' => $slug],
                ['name' => $catName, 'slug' => $slug]
            );

            foreach ($items as $item) {
                MenuItem::firstOrCreate(
                    ['name' => $item['name'], 'category_id' => $cat->id],
                    [
                        'name' => $item['name'],
                        'description' => $item['description'] ?? '',
                        'price' => $item['price'],
                        'is_featured' => $item['is_featured'] ?? false,
                        'is_available' => true,
                        'category_id' => $cat->id,
                    ]
                );
            }
        }
    }
}
