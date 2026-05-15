<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{

    public function create(Request $request)
    {
        $request->validate([
            'items' => 'required|array|min:1',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
            'address_id' => 'required|exists:addresses,id',
        ]);

        $user = $request->user();
        $items = $request->input('items');
        $addressId = $request->input('address_id');

        // Ensure address belongs to the authenticated user
        $address = \App\Models\Address::where('id', $addressId)
            ->where('customer_id', $user->id)
            ->first();

        if (!$address) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid address selected.',
            ], 403);
        }

        $total = 0;
        $orderItems = [];

        DB::beginTransaction();

        try {
            foreach ($items as $item) {
                $product = Product::findOrFail($item['product_id']);

                if ($product->stock < $item['quantity']) {
                    throw new \Exception("Insufficient stock for product: {$product->name}");
                }

                // Deduct stock
                $product->decrement('stock', $item['quantity']);

                $lineTotal = $product->price * $item['quantity'];
                $total += $lineTotal;

                $orderItems[] = [
                    'product_id' => $product->id,
                    'quantity' => $item['quantity'],
                    'price' => $product->price,
                ];
            }

            // Create order with address_id
            $order = Order::create([
                'user_id'    => $user->id,
                'address_id' => $addressId,
                'total_price' => $total,
                'status'     => 'pending',
            ]);

            // Create order items
            foreach ($orderItems as $orderItem) {
                $order->items()->create($orderItem);
            }

            DB::commit();

            return response()->json([
                'status' => true,

                'message' => 'Order placed successfully.',
                'order_id' => $order->id,
                'total_price' => $total,
                'order_status' => $order->status,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json([
                'status' => false,
                'message' => 'Order failed.',
                'error' => $e->getMessage(),
            ], 400);
        }
    }



    public function orderHistory(Request $request)
    {
        $user = $request->user();
        $perPage = $request->get('per_page', 10);

        $orders = Order::with(['items.product'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->paginate($perPage);

        // Format response
        $orders->getCollection()->transform(function ($order) {
            return [
                'id' => $order->id,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'created_at' => $order->created_at->toDateTimeString(),
                'items' => $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? null,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'image_url' => $item->product->image
                            ? asset('storage/' . $item->product->image)
                            : asset('images/default_product.png'),
                    ];
                }),
            ];
        });

        return response()->json([
            'status' => true,
            'message' => 'Order history retrieved successfully.',
            'data' => $orders
        ]);
    }

    public function showOrder($id, Request $request)
    {
        $user = $request->user();

        $order = Order::with(['items.product', 'address'])
            ->where('user_id', $user->id)
            ->find($id);

        if (!$order) {
            return response()->json([
                'status' => false,
                'message' => 'Order not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'message' => 'Order details retrieved successfully.',
            'data' => [
                'id' => $order->id,
                'total_price' => $order->total_price,
                'status' => $order->status,
                'created_at' => $order->created_at->toDateTimeString(),

                'address' => $order->address ? [
                    'id' => $order->address->id,
                    'address_line1' => $order->address->address_line1,
                    'address_line2' => $order->address->address_line2,
                    'city' => $order->address->city,
                    'state' => $order->address->state,
                    'postal_code' => $order->address->postal_code,
                    'country' => $order->address->country,
                    'is_default' => $order->address->is_default,
                ] : null,

                'items' => $order->items->map(function ($item) {
                    return [
                        'product_id' => $item->product_id,
                        'product_name' => $item->product->name ?? null,
                        'quantity' => $item->quantity,
                        'price' => $item->price,
                        'image_url' => $item->product->image
                            ? asset('storage/' . $item->product->image)
                            : asset('images/default_product.png'),
                    ];
                }),
            ]
        ]);
    }
}
