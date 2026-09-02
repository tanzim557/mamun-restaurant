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
                'name' => 'Nazrul Hotel Admin',
                'email' => 'admin@mamun.com',
                'password' => 'admin',
                'role' => 'ADMIN',
            ]);
        }

        // Full Authentic Price Menu of Nazrul Hotel & Restaurant, Satkhira
        $menuData = [
            'ভাত, খিচুড়ি ও রুটি' => [
                [
                    'name' => 'ভাত ফুল',
                    'price' => 20,
                    'description' => 'গরম ধোঁয়া ওঠা এক প্লেট সাদা ভাত (ফুল প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ভাত হাফ',
                    'price' => 10,
                    'description' => 'সাদা ভাত (হাফ প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1516684732162-798a0062be99?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'খিচুড়ী ফুল',
                    'price' => 120,
                    'description' => 'ঘিয়ে ভাজা স্পেশাল ভুনা খিচুড়ি (ফুল প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1633945274405-b6c8069047b0?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'খিচুড়ী হাফ',
                    'price' => 60,
                    'description' => 'সুস্বাদু গরম ভুনা খিচুড়ি (হাফ প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1633945274405-b6c8069047b0?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'পরোটা পিচ',
                    'price' => 10,
                    'description' => 'মুচমুচে গরম তেলের পরোটা (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1601050690597-df0568f70950?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'রুটি পিচ',
                    'price' => 10,
                    'description' => 'হাতে বানানো ফ্রেশ গরম আটার রুটি (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1565557623262-b51c2513a641?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'মোগলায় পরোটা ডাবল',
                    'price' => 80,
                    'description' => 'ডাবল ডিম ও মশলার পুরে ভাজা স্পেশাল মোগলাই পরোটা।',
                    'image' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'মোগলায় পরোটা',
                    'price' => 50,
                    'description' => 'মুচমুচে ফ্রেশ মোগলাই পরোটা সালাদ সহ।',
                    'image' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
            ],

            'গরু ও হাঁসের মাংস' => [
                [
                    'name' => 'চুইঝালের গরু ফুল',
                    'price' => 320,
                    'description' => 'সাতক্ষীরার বিখ্যাত অরিজিনাল চুইঝাল দিয়ে রান্না করা ঐতিহ্যবাহী গরুর মাংস (ফুল প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'চুইঝালের গরু হাফ',
                    'price' => 160,
                    'description' => 'সাতক্ষীরার অরিজিনাল চুইঝালের গরুর মাংস (হাফ প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'কালাভুনা ফুল',
                    'price' => 360,
                    'description' => 'স্পেশাল খাঁটি মশলায় কড়া করে ভাজা চট্টগ্রামের ঐতিহ্যবাহী গরুর কালাভুনা (ফুল প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'কালাভুনা হাফ',
                    'price' => 180,
                    'description' => 'কালো ভুনার স্পেশাল গরুর মাংস (হাফ প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1603894584373-5ac82b2ae398?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'হাঁসের মাংস প্লেট',
                    'price' => 400,
                    'description' => 'দেশি হাঁসের ঝাল ভুনা ও ঘন ঝোল (প্রতি প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1589302168068-964664d93dc0?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'গরুর নেহারী',
                    'price' => 200,
                    'description' => 'ধীরে ধীরে সেদ্ধ করা সুস্বাদু হাড় ও নালির গরুর নেহারী পায়া ঝোল।',
                    'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
            ],

            'মুরগির পদ' => [
                [
                    'name' => 'সোনালী মুরগি পিচ',
                    'price' => 100,
                    'description' => 'দেশি স্বাদের সোনালী মুরগির কষা ভুনা (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1606728035253-49e8a23146de?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'ব্রয়লার ফুল',
                    'price' => 100,
                    'description' => 'মশলাদার ব্রয়লার মুরগির মাংস ফুল প্লেট।',
                    'image' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ব্রয়লার হাফ',
                    'price' => 70,
                    'description' => 'ব্রয়লার মুরগির সুস্বাদু মাংস হাফ প্লেট।',
                    'image' => 'https://images.unsplash.com/photo-1562967914-608f82629710?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'মুরগীর লটপটি প্লেট',
                    'price' => 70,
                    'description' => 'মুরগির কলিজা ও গিলা দিয়ে তৈরি লোভনীয় ঝাল লটপটি।',
                    'image' => 'https://images.unsplash.com/photo-1626777552726-4a6b54c97e46?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
            ],

            'মাছের বাহার' => [
                [
                    'name' => 'ইলিশ মাছ পিচ',
                    'price' => 250,
                    'description' => 'পদ্মা নদীর খাঁটি তাজা ইলিশ মাছ ভাজা / ঝোল (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'ভেটকি মাছ পিচ',
                    'price' => 220,
                    'description' => 'সুন্দরবনের তাজা ভেটকি মাছের ভুনা / ফ্রাই (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'রূপচাঁদা ফ্রাই ফুল',
                    'price' => 320,
                    'description' => 'মশলায় মাখানো সামুদ্রিক রূপচাঁদা মাছ কড়া ফ্রাই (ফুল)।',
                    'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
                [
                    'name' => 'রূপচাঁদা ফ্রাই হাফ',
                    'price' => 160,
                    'description' => 'সামুদ্রিক রূপচাঁদা ফ্রাই (হাফ)।',
                    'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'পারসি মাছ পিচ',
                    'price' => 200,
                    'description' => 'সাতক্ষীরার মিষ্টি পারসে মাছের হালকা ঝোল (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'কাতলা মাছ পিচ',
                    'price' => 160,
                    'description' => 'বড় কাতলা মাছের পেটি ও দাগা ভুনা (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1544025162-d76694265947?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'টেংরা মাছ প্লেট',
                    'price' => 100,
                    'description' => 'নদীর দেশি তাজা টেংরা মাছের ঝাল চচ্চড়ি।',
                    'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'খরখুল্লো মাছ পিচ',
                    'price' => 100,
                    'description' => 'লোকাল তাজা খরখুল্লো মাছ ভুনা (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ছোট মাছ প্লেট',
                    'price' => 80,
                    'description' => 'দেশি নানা পদের ছোট মাছের মশলাদার চচ্চড়ি।',
                    'image' => 'https://images.unsplash.com/photo-1534422298391-e4f8c172dddb?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'সামুদ্রিক ছোট মাছ',
                    'price' => 80,
                    'description' => 'তাজা সামুদ্রিক ছোট মাছের চচ্চড়ি।',
                    'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'মুড়োঘন্টো',
                    'price' => 80,
                    'description' => 'বড় মাছের মাথা ও মুগডাল দিয়ে ঐতিহ্যবাহী মুড়োঘন্ট।',
                    'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'বাটা মাছ পিচ',
                    'price' => 70,
                    'description' => 'দেশি বাটা মাছের ভুনা (প্রতি পিচ)।',
                    'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'তেলাপিয়া মাছ',
                    'price' => 60,
                    'description' => 'তাজা তেলাপিয়া মাছ ভাজা / ভুনা।',
                    'image' => 'https://images.unsplash.com/photo-1519708227418-c8fd9a32b7a2?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
            ],

            'ডাল, ডিম, ভর্তা ও ভাজি' => [
                [
                    'name' => 'ডিম ডাল প্লেট',
                    'price' => 40,
                    'description' => 'ঘন ডালের ভেতর গোটা ডিম রান্না (প্রতি প্লেট)।',
                    'image' => 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ডিম সবজী প্লেট',
                    'price' => 40,
                    'description' => 'তাজা মিক্সড সবজির সাথে ডিম ভুনা।',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ডিম মামলেট',
                    'price' => 20,
                    'description' => 'পেঁয়াজ-মরিচ দিয়ে গরম তাওয়ায় ভাজা ডিম অমলেট।',
                    'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ডিম পোস্ট',
                    'price' => 20,
                    'description' => 'তেলে ভাজা নরম কুসুমের এগ পোচ।',
                    'image' => 'https://images.unsplash.com/photo-1525351484163-7529414344d8?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'মুগডাল প্লেট',
                    'price' => 20,
                    'description' => 'ঘিয়ে বাগার দেওয়া স্পেশাল সোনালী মুগডাল।',
                    'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ঘনডাল প্লেট',
                    'price' => 20,
                    'description' => 'রসুনের ফোড়ন দেওয়া স্পেশাল ঘন মসুর ডাল।',
                    'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ছোলার ডাল প্লেট',
                    'price' => 20,
                    'description' => 'নরম তুলতুলে সুস্বাদু ছোলার ডাল ভুনা।',
                    'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'পাতলা ডাল প্লেট',
                    'price' => 15,
                    'description' => 'ভাতের সাথে খাওয়ার মতো হালকা পাতলা মসুর ডাল।',
                    'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'নাস্তার ঘনডাল',
                    'price' => 15,
                    'description' => 'সকালের পরোটা ও রুটির সাথে খাওয়ার ঘন ডাল।',
                    'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ভাতের ঘনডাল',
                    'price' => 15,
                    'description' => 'দুপুর ও রাতের খাবারের জন্য স্পেশাল ডাল।',
                    'image' => 'https://images.unsplash.com/photo-1546833999-b9f581a1996d?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'উচ্ছে চিংড়ি',
                    'price' => 50,
                    'description' => 'তাজা ছোট চিংড়ি ও তিত করলা ভাজি।',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'বেগুন ভর্তা',
                    'price' => 20,
                    'description' => 'সরিষার তেল ও শুকনো মরিচ দিয়ে পোড়া বেগুন ভর্তা।',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'ঢেঁড়স ভাজি',
                    'price' => 30,
                    'description' => 'সবুজ তাজা ঢেঁড়স ফ্রাই।',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'লাল শাক',
                    'price' => 30,
                    'description' => 'রসুন ও কাঁচামরিচ দিয়ে লাল শাক ভাজি।',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'তরকারি',
                    'price' => 30,
                    'description' => 'হালকা ঝোলের তাজা সিজনাল সবজি তরকারি।',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
                [
                    'name' => 'আলু ভর্তা',
                    'price' => 10,
                    'description' => 'খাঁটি সরিষার তেলে মাখানো আলু ভর্তা।',
                    'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => false
                ],
            ],

            'দই ও মিষ্টি' => [
                [
                    'name' => 'গ্লাস দই',
                    'price' => 30,
                    'description' => 'সাতক্ষীরার খাঁটি খাঁটি মিষ্টি ও ঠান্ডা গ্লাস দই।',
                    'image' => 'https://images.unsplash.com/photo-1571212515416-fef01fc43637?w=600&auto=format&fit=crop&q=80',
                    'is_featured' => true
                ],
            ],
        ];

        // Clear existing menu items and replace with the authentic Nazrul Hotel banner menu
        MenuItem::query()->delete();
        Category::query()->delete();

        foreach ($menuData as $catName => $items) {
            $slug = Str::slug($catName) ?: 'cat-' . Str::random(5);
            $cat = Category::create([
                'name' => $catName,
                'slug' => $slug
            ]);

            foreach ($items as $item) {
                MenuItem::create([
                    'name' => $item['name'],
                    'description' => $item['description'] ?? '',
                    'price' => $item['price'],
                    'image' => $item['image'] ?? null,
                    'is_featured' => $item['is_featured'] ?? false,
                    'is_available' => true,
                    'category_id' => $cat->id,
                ]);
            }
        }

        // Raw Materials / Kitchen Stock Items
        \App\Models\StockItem::query()->delete();
        $stockItems = [
            ['name' => 'মিনিকেট চাল', 'category' => 'চাল ও আটা', 'quantity' => 75.0, 'used_quantity' => 25.0, 'unit' => 'কেজি', 'min_quantity' => 20.0, 'last_price' => 70.0],
            ['name' => 'সয়াবিন তেল', 'category' => 'তেল ও ঘি', 'quantity' => 40.0, 'used_quantity' => 15.0, 'unit' => 'লিটার', 'min_quantity' => 10.0, 'last_price' => 175.0],
            ['name' => 'মসুর ডাল', 'category' => 'ডাল', 'quantity' => 22.0, 'used_quantity' => 8.0, 'unit' => 'কেজি', 'min_quantity' => 5.0, 'last_price' => 130.0],
            ['name' => 'মুগ ডাল', 'category' => 'ডাল', 'quantity' => 14.0, 'used_quantity' => 4.0, 'unit' => 'কেজি', 'min_quantity' => 4.0, 'last_price' => 145.0],
            ['name' => 'তাজা গরুর মাংস', 'category' => 'মাংস', 'quantity' => 28.0, 'used_quantity' => 22.0, 'unit' => 'কেজি', 'min_quantity' => 8.0, 'last_price' => 780.0],
            ['name' => 'সাতক্ষীরার চুইঝাল', 'category' => 'মশলা', 'quantity' => 6.0, 'used_quantity' => 2.0, 'unit' => 'কেজি', 'min_quantity' => 2.0, 'last_price' => 900.0],
            ['name' => 'দেশি হাঁস', 'category' => 'মাংস', 'quantity' => 12.0, 'used_quantity' => 6.0, 'unit' => 'পিচ', 'min_quantity' => 4.0, 'last_price' => 550.0],
            ['name' => 'সোনালী মুরগি', 'category' => 'মাংস', 'quantity' => 20.0, 'used_quantity' => 12.0, 'unit' => 'পিচ', 'min_quantity' => 6.0, 'last_price' => 280.0],
            ['name' => 'তাজা ইলিশ মাছ', 'category' => 'মাছ', 'quantity' => 8.0, 'used_quantity' => 5.0, 'unit' => 'পিচ', 'min_quantity' => 3.0, 'last_price' => 850.0],
            ['name' => 'তাজা ভেটকি মাছ', 'category' => 'মাছ', 'quantity' => 10.0, 'used_quantity' => 4.0, 'unit' => 'কেজি', 'min_quantity' => 3.0, 'last_price' => 650.0],
            ['name' => 'পিঁয়াজ', 'category' => 'সবজি ও মশলা', 'quantity' => 30.0, 'used_quantity' => 12.0, 'unit' => 'কেজি', 'min_quantity' => 8.0, 'last_price' => 65.0],
            ['name' => 'রসুন ও আদা', 'category' => 'সবজি ও মশলা', 'quantity' => 12.0, 'used_quantity' => 4.0, 'unit' => 'কেজি', 'min_quantity' => 3.0, 'last_price' => 180.0],
            ['name' => 'আলু', 'category' => 'সবজি ও মশলা', 'quantity' => 45.0, 'used_quantity' => 15.0, 'unit' => 'কেজি', 'min_quantity' => 10.0, 'last_price' => 35.0],
            ['name' => 'আটা ও ময়দা', 'category' => 'চাল ও আটা', 'quantity' => 25.0, 'used_quantity' => 8.0, 'unit' => 'কেজি', 'min_quantity' => 6.0, 'last_price' => 48.0],
        ];

        foreach ($stockItems as $si) {
            \App\Models\StockItem::create($si);
        }
    }
}
