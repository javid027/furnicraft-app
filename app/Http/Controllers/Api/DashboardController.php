<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Banner;
use App\Models\Category;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        // Active banners with full image URL
        $banners = Banner::where('is_active', 1)
            ->select('id', 'title', 'description', 'image')
            ->get()
            ->makeHidden(['image']) // optionally hide raw image field
            ->append('image_url');

        // Featured categories with full image URL
        $categories = Category::where('is_feature', 1)
            ->select('id', 'name', 'status', 'image')
            ->get()
            ->makeHidden(['image'])
            ->append('image_url');

        // Featured products (limit 4) with full image URL
        $products = Product::where('is_featured', 1)
            ->select('id', 'name', 'category_id', 'price', 'stock', 'color_name', 'quantity', 'image')
            ->limit(4)
            ->get()
            ->makeHidden(['image'])
            ->append('image_url');

        return response()->json([
            'status' => true,
            'message' => 'Dashboard fetched successfully.',
            'data' => [
                'banners' => $banners,
                'categories' => $categories,
                'products' => $products,
            ],
        ]);
    }
}
