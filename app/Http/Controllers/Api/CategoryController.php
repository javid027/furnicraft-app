<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        // Optional: allow frontend to pass ?per_page=10
        $perPage = $request->get('per_page', 10); // default 10 per page

        // Fetch paginated categories
        $categories = Category::select('id', 'name', 'status', 'image', 'is_feature')
            ->paginate($perPage);

        // Append full image URL to each category
        $categories->getCollection()->transform(function ($category) {
            return [
                'id' => $category->id,
                'name' => $category->name,
                'status' => $category->status,
                'is_feature' => $category->is_feature,
                'image_url' => $category->image
                    ? asset('storage/' . $category->image)
                    : asset('images/default_category.png'),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Category fetched successfully.',
            'data' => $categories,
        ]);
    }
}
