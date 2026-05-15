<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AddressController extends Controller
{
    // List all addresses of the authenticated customer
    public function index()
    {
        /** @var Customer $customer */
        $customer = Auth::user();

        return response()->json([
            'status' => true,
            'message' => 'address fetched successfully.',
            'data' => $customer->addresses
        ]);
    }

    // Store a new address
    public function store(Request $request)
    {
        /** @var Customer $customer */
        $customer = Auth::user();

        $validated = $request->validate([
            'address_line1' => 'required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'required|string|max:100',
            'state' => 'required|string|max:100',
            'postal_code' => 'required|string|max:20',
            'country' => 'required|string|max:100',
            'is_default' => 'boolean',
        ]);

        if (!empty($validated['is_default']) && $validated['is_default']) {
            // Reset other default addresses
            $customer->addresses()->update(['is_default' => false]);
        }

        $address = $customer->addresses()->create($validated);

        return response()->json([
            'status' => true,
            'message' => 'address fetched successfully.',
            'data' => $address
        ]);
    }

    // Show a single address (must belong to the customer)
    public function show(Address $address)
    {
        /** @var Customer $customer */
        $customer = Auth::user();

        // if ($address->customer_id !== $customer->id) {
        //     return response()->json(['message' => 'Unauthorized'], 403);
        // }

        return response()->json([
            'status' => true,
            'message' => 'address fetched successfully.',
            'data' => $address
        ]);
    }

    public function update(Request $request, Address $address)
    {
        /** @var Customer $customer */
        $customer = Auth::user();

        if (!$customer || $address->customer_id !== $customer->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $validated = $request->validate([
            'address_line1' => 'sometimes|required|string|max:255',
            'address_line2' => 'nullable|string|max:255',
            'city' => 'sometimes|required|string|max:100',
            'state' => 'sometimes|required|string|max:100',
            'postal_code' => 'sometimes|required|string|max:20',
            'country' => 'sometimes|required|string|max:100',
            'is_default' => 'boolean',
        ]);

        // If set as default → reset other addresses
        if (!empty($validated['is_default']) && $validated['is_default']) {
            $customer->addresses()->update(['is_default' => false]);
        }

        $address->update($validated);

        return response()->json([
            'status' => true,
            'message' => 'Address updated successfully',
            'data' => $address
        ]);
    }

    // Delete an address
    public function destroy(Address $address)
    {
        /** @var Customer $customer */
        $customer = Auth::user();

        if ($address->customer_id !== $customer->id) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $address->delete();

        return response()->json([
            'status' => true,
            'message' => 'Address deleted successfully'
        ]);
    }

    // Get the default address of the authenticated customer
    public function defaultAddress()
    {
        /** @var Customer $customer */
        $customer = Auth::user();

        $defaultAddress = $customer->addresses()->where('is_default', true)->first();

        if (!$defaultAddress) {
            return response()->json(['message' => 'No default address set'], 404);
        }

        return response()->json($defaultAddress);
    }

    // Set an address as default
    public function setDefault(Address $address)
    {
        /** @var Customer $customer */
        $customer = Auth::user();

        if ($address->customer_id !== $customer->id) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        // Reset all addresses is_default to false
        $customer->addresses()->update(['is_default' => false]);

        // Set this address as default
        $address->is_default = true;
        $address->save();

        return response()->json(['message' => 'Default address updated', 'address' => $address]);
    }
}
