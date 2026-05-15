<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Wishlist;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 10);
        $userId = auth()->id();

        $query = Product::query();

        if ($request->has('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 🔎 Search filter
        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('color_name', 'LIKE', "%{$search}%");
            });
        }

// Sorting
if ($request->filled('sort')) {
    switch ($request->sort) {
        case 'price_asc':
            $query->orderBy('price', 'asc');
            break;

        case 'price_desc':
            $query->orderBy('price', 'desc');
            break;
    }
}
        // Only check wishlist if user logged in
        if ($userId) {
            $query->withCount([
                'wishlists as is_wishlisted' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                }
            ]);
        }

        $products = $query->paginate($perPage);

        $products->getCollection()->transform(function ($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'price' => $product->price,
                'stock' => $product->stock,
                'color_name' => $product->color_name,
                'quantity' => $product->quantity,
                'is_featured' => $product->is_featured,
                'image_url' => $product->image_url,

                // convert count → boolean
                'is_wishlisted' => isset($product->is_wishlisted) && $product->is_wishlisted > 0,
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Products fetched successfully.',
            'data' => $products,
        ]);
    }


    public function show($id)
    {
        $product = Product::select(
            'id',
            'name',
            'category_id',
            'price',
            'stock',
            'color_name',
            'quantity',
            'image',
            'is_featured'
        )->find($id);

        if (!$product) {
            return response()->json([
                'status' => false,
                'message' => 'Product not found'
            ], 404);
        }

        $userId = Auth::id();

        // Check if product is in wishlist
        $isWishlisted = false;

        if ($userId) {
            $isWishlisted = Wishlist::where('user_id', $userId)
                ->where('product_id', $product->id)
                ->exists();
        }

        return response()->json([
            'status' => true,
            'message' => 'Product Detail fetched successfully.',
            'data' => [
                'id' => $product->id,
                'name' => $product->name,
                'category_id' => $product->category_id,
                'price' => $product->price,
                'stock' => $product->stock,
                'color_name' => $product->color_name,
                'quantity' => $product->quantity,
                'is_featured' => $product->is_featured,
                'image_url' => $product->image_url,
                'is_wishlisted' => $isWishlisted
            ],
        ]);
    }
}
