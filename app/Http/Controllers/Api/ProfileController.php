<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    // Get profile
    public function profile(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'status' => false,
                'message' => 'Token is invalid or expired. Please log in again.',
            ], 401);
        }

        // Add a full image URL to the response
        $user->user_image_url = $user->user_image
            ? asset('storage/' . $user->user_image)
            : asset('storage/images/default-avatar.png');

        return response()->json([
            'status' => true,
            'message' => 'Profile fetched successfully.',
            'user' => $user,
        ]);
    }



    // Update profile (name, email, mobile, etc.)
    public function updateProfile(Request $request)
    {
        $user = $request->user();

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'email' => 'required|email|unique:customers,email,' . $user->id,
            'user_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:2048',
        ]);

        // Handle image upload
        if ($request->hasFile('user_image')) {

            // Delete old image if it exists
            if ($user->user_image && \Storage::disk('public')->exists($user->user_image)) {
                \Storage::disk('public')->delete($user->user_image);
            }

            // Store the new image
            $data['user_image'] = $request->file('user_image')->store('user_images', 'public');
        }

        $user->update($data);

        // Add image URL to response
        $user->user_image_url = $user->user_image
            ? asset('storage/' . $user->user_image)
            : asset('storage/images/default-avatar.png');

        return response()->json([
            'status' => true,
            'message' => 'Profile updated successfully.',
            'user' => $user
        ]);
    }


    public function logout(Request $request)
    {
        $user = $request->user();

        if (!$user || !$user->currentAccessToken()) {
            return response()->json([
                'status' => false,
                'message' => 'Token is invalid or already logged out.',
            ], 401);
        }

        $user->currentAccessToken()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Logged out successfully.',
        ]);
    }
}
