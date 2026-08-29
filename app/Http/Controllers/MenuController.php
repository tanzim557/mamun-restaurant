<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\MenuItem;

class MenuController extends Controller
{
    public function index()
    {
        $categories = Category::with('menuItems')->get();
        return response()->json($categories);
    }

    public function items()
    {
        $items = MenuItem::with('category')->get();
        return response()->json($items);
    }

    public function categories()
    {
        $categories = Category::all();
        return response()->json($categories);
    }
}
