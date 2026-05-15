<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WishlistController extends Controller
{
    // ✅ View wishlist
    public function index()
    {
        $wishlist = Wishlist::with(['product:id,name,category_id,price,stock,color_name,quantity,image'])
            ->where('user_id', Auth::id())
            ->get();

            
        return response()->json([
             'status' => true,
            'message' => 'Wishlist fetched successfully.',
              'data' => $wishlist,
        ]);
    }

    // ✅ Add to wishlist
    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $exists = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $request->product_id)
            ->exists();

        if ($exists) {
            return response()->json(['status'=>false,'message' => 'Product already in wishlist'], 409);
        }

        $wishlist = Wishlist::create([
            'user_id' => Auth::id(),
            'product_id' => $request->product_id,
        ]);

        return response()->json(['status'=>true,'message' => 'Added to wishlist', 'wishlist' => $wishlist], 201);
    }

    // ❌ Remove from wishlist
    public function destroy($product_id)
    {
        $wishlistItem = Wishlist::where('user_id', Auth::id())
            ->where('product_id', $product_id)
            ->first();

        if (!$wishlistItem) {
            return response()->json(['status'=>true,'message' => 'Product not found in wishlist'], 404);
        }

        $wishlistItem->delete();

        return response()->json(['status'=>true,'message' => 'Removed from wishlist']);
    }

    public function toggle(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
        ]);

        $userId = Auth::id();
        $productId = $request->product_id;

        $wishlistItem = Wishlist::where('user_id', $userId)
            ->where('product_id', $productId)
            ->first();

        if ($wishlistItem) {
            $wishlistItem->delete();
            return response()->json([
                'status'=>true,
                'message' => 'Removed from wishlist',
            ]);
        }

        $newItem = Wishlist::create([
            'user_id' => $userId,
            'product_id' => $productId,
        ]);

        return response()->json([
            'message' => 'Added to wishlist',
            'status' => 'added',
            'wishlist' => $newItem,
        ]);
    }
}
