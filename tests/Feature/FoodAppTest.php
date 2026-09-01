<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Category;
use App\Models\MenuItem;

class FoodAppTest extends TestCase
{
    use RefreshDatabase;

    public function test_customer_facing_food_app_pages_load_successfully()
    {
        $this->get('/')->assertStatus(200);
        $this->get('/menu')->assertStatus(200);
        $this->get('/order')->assertStatus(200);
        $this->get('/track')->assertStatus(200);
        $this->get('/contact')->assertStatus(200);
        $this->get('/about')->assertStatus(200);
    }

    public function test_admin_pages_load_successfully()
    {
        $this->get('/admin')->assertStatus(200);
        $this->get('/admin/login')->assertStatus(200);
    }

    public function test_menu_and_categories_api()
    {
        $cat = Category::create([
            'name' => 'গরু ও চুইঝাল',
            'slug' => 'beef-chui'
        ]);

        MenuItem::create([
            'name' => 'চুইঝাল গরুর কালাভুনা',
            'price' => 220,
            'category_id' => $cat->id,
            'is_available' => true
        ]);

        $this->get('/api/menu/categories')->assertStatus(200)->assertJsonStructure([
            '*' => ['id', 'name', 'slug']
        ]);

        $this->get('/api/menu/items')->assertStatus(200);
    }

    public function test_restaurant_status_api_and_toggle()
    {
        $res = $this->getJson('/api/restaurant/status');
        $res->assertStatus(200);
        $this->assertArrayHasKey('isOpen', $res->json());

        // Toggle to false (hotel closed)
        $toggleRes = $this->postJson('/api/admin/restaurant/status', ['isOpen' => false]);
        $toggleRes->assertStatus(200)->assertJson([
            'success' => true,
            'status' => ['isOpen' => false]
        ]);

        // Attempting to place order when closed should fail with 400
        $orderData = [
            'customerName' => 'তানজিম আহমেদ',
            'phoneNumber' => '01988976269',
            'address' => 'সাতক্ষীরা সদর',
            'items' => [['name' => 'চুইঝাল গরুর কালাভুনা', 'price' => 220, 'qty' => 1]]
        ];
        $this->postJson('/api/orders', $orderData)->assertStatus(400);

        // Toggle back to true (hotel open)
        $toggleRes2 = $this->postJson('/api/admin/restaurant/status', ['isOpen' => true]);
        $toggleRes2->assertStatus(200)->assertJson([
            'success' => true,
            'status' => ['isOpen' => true]
        ]);
    }

    public function test_order_placement_and_tracking_flow()
    {
        // Ensure hotel is open
        $this->postJson('/api/admin/restaurant/status', ['isOpen' => true]);

        $orderData = [
            'customerName' => 'তানজিম আহমেদ',
            'phoneNumber' => '01988976269',
            'address' => 'সাতক্ষীরা সদর, উকিলবার গেট',
            'note' => 'ঝাল বেশি দিবেন',
            'items' => [
                [
                    'name' => 'চুইঝাল গরুর কালাভুনা',
                    'price' => 220,
                    'qty' => 2
                ],
                [
                    'name' => 'স্পেশাল চুইঝাল হাঁসের মাংস',
                    'price' => 250,
                    'qty' => 1
                ]
            ]
        ];

        $response = $this->postJson('/api/orders', $orderData);
        $response->assertStatus(201);
        $response->assertJson([
            'success' => true
        ]);

        $responseData = $response->json();
        $this->assertNotEmpty($responseData['order']['id']);
        $this->assertNotEmpty($responseData['order']['shortId']);
        $this->assertEquals(690, $responseData['order']['totalAmount']);

        // Test Track endpoint
        $trackResponse = $this->getJson('/api/orders/track?query=' . $responseData['order']['shortId']);
        $trackResponse->assertStatus(200)->assertJson([
            'success' => true
        ]);
        $this->assertEquals('তানজিম আহমেদ', $trackResponse->json('order.customerName'));
    }
}
