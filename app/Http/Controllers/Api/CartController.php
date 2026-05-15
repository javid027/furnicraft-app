<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CartController extends Controller
{
    public function index()
    {
        // dd('user=',Auth::user()->name);
        $cartItems = Cart::with(['product:id,name,category_id,price,stock,color_name,image'])
            ->where('customer_id', Auth::user()->id)
            ->get();

        return response()->json([
            'status' => true,
            'message' => 'cart fetched successfully.',
            'data' => $cartItems,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'address_id' => 'nullable|exists:addresses,id',
        ]);

        $userId = $request->user()->id;
        $addressId = $request->address_id;

        // Validate that the address belongs to the current user (optional but secure)
        if ($addressId) {
            $addressBelongsToUser = \App\Models\Address::where('id', $addressId)
                ->where('customer_id', $userId)
                ->exists();

            if (!$addressBelongsToUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid address selected.',
                ], 403);
            }
        }

        // Find or create the cart item
        $cartItem = Cart::firstOrCreate(
            ['customer_id' => $userId, 'product_id' => $request->product_id],
            ['quantity' => 0, 'address_id' => $addressId]
        );

        // Update address if needed
        if ($addressId && $cartItem->address_id !== $addressId) {
            $cartItem->address_id = $addressId;
        }

        $cartItem->increment('quantity', $request->quantity);
        $cartItem->save();

        return response()->json([
            'status' => true,
            'message' => 'Product added to cart',
            'cart' => $cartItem->fresh(),
        ]);
    }


    public function update(Request $request, $product_id)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
            'address_id' => 'nullable|exists:addresses,id',
        ]);

        $userId = Auth::id();
        $addressId = $request->address_id;

        // Check ownership of address
        if ($addressId) {
            $addressBelongsToUser = \App\Models\Address::where('id', $addressId)
                ->where('customer_id', $userId)
                ->exists();

            if (!$addressBelongsToUser) {
                return response()->json([
                    'status' => false,
                    'message' => 'Invalid address selected.',
                ], 403);
            }
        }

        $cartItem = Cart::where('customer_id', $userId)
            ->where('product_id', $product_id)
            ->firstOrFail();

        $cartItem->quantity = $request->quantity;

        if ($addressId) {
            $cartItem->address_id = $addressId;
        }

        $cartItem->save();

        return response()->json([
            'status' => true,
            'message' => 'Cart updated successfully.',
            'cart' => $cartItem->fresh(),
        ]);
    }


    public function destroy($product_id)
    {
        Cart::where('customer_id', Auth::id())->where('product_id', $product_id)->delete();

        return response()->json([
            'status' => true,
            'message' => 'Product removed from cart',

        ]);
    }

    public function clear()
    {
        Cart::where('customer_id', Auth::id())->delete();

        return response()->json(['status' => true, 'message' => 'Cart cleared']);
    }
}
