<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class CustomerAuthController extends Controller
{
    /**
     * OTP validity in minutes
     */
    private int $otpExpiryMinutes = 5;

    /**
     * Generate secure OTP
     */
    private function generateOtp(): string
    {
        return (string) random_int(100000, 999999);
    }

    /**
     * =========================
     * REGISTER
     * =========================
     */
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'     => 'required|string',
            'email'    => 'required|email|unique:customers,email',
            'mobile'   => 'required|digits:10|unique:customers,mobile',
            'location' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'errors' => $validator->errors()->all(),
            ], 422);
        }

        $otp = $this->generateOtp();

        $customer = Customer::create([
            'name'             => $request->name,
            'email'            => $request->email,
            'mobile'           => $request->mobile,
            'location'         => $request->location,
            'otp'              => $otp,
            'otp_expires_at'   => now()->addMinutes($this->otpExpiryMinutes),
            'otp_verified'     => false,
            'is_active'        => true,
        ]);

        // TODO: Send $otp via SMS gateway

        return response()->json([
            'status'  => true,
            'otp'  => $otp,
            'message' => 'OTP sent to your mobile number.',
        ]);
    }

    /**
     * =========================
     * VERIFY REGISTER OTP
     * =========================
     */
    public function verifyRegisterOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp'    => 'required|digits:6',
        ]);

        $customer = Customer::where('mobile', $request->mobile)->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        if (!$customer->otp_expires_at || now()->greaterThan($customer->otp_expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired.',
            ], 401);
        }

        if ($request->otp != $customer->otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP.',
            ], 401);
        }

        // Revoke previous tokens
        $customer->tokens()->delete();

        $customer->update([
            'otp_verified'   => true,
            'otp'            => null,
            'otp_expires_at' => null,
        ]);

        $tokenName = $request->userAgent() ?? 'customer_app';

        $token = $customer->createToken(
            $tokenName,
            ['customer']
        )->plainTextToken;

        return response()->json([
            'status'  => true,
            'message' => 'Registration verified successfully.',
            'token'   => $token,
            'token_type' => 'Bearer',
            'customer' => [
                'id'     => $customer->id,
                'name'   => $customer->name,
                'mobile' => $customer->mobile,
                'email'  => $customer->email,
                'user_image' => $customer->user_image
                ? asset('storage/' . $customer->user_image)
                : null,
            ],
        ]);
    }

    /**
     * =========================
     * LOGIN
     * =========================
     */
    public function login(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
        ]);

        $customer = Customer::where('mobile', $request->mobile)->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Mobile number not found.',
            ], 404);
        }

        if (!$customer->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Please contact support.',
            ], 403);
        }

        $otp = $this->generateOtp();

        $customer->update([
            'otp'            => $otp,
            'otp_verified'   => false,
            'otp_expires_at' => now()->addMinutes($this->otpExpiryMinutes),
        ]);

        // TODO: Send $otp via SMS

        return response()->json([
            'status'  => true,
            'otp'  => $otp,
            'message' => 'OTP sent to your mobile number.',
        ]);
    }

    /**
     * =========================
     * VERIFY LOGIN OTP
     * =========================
     */
    public function verifyLoginOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
            'otp'    => 'required|digits:6',
        ]);

        $customer = Customer::where('mobile', $request->mobile)->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found.',
            ], 404);
        }

        // Check OTP expiration
        if (!$customer->otp_expires_at || now()->greaterThan($customer->otp_expires_at)) {
            return response()->json([
                'status' => false,
                'message' => 'OTP expired.',
            ], 401);
        }

        // Compare OTP directly (no Hash check)
        if ($request->otp != $customer->otp) {
            return response()->json([
                'status' => false,
                'message' => 'Invalid OTP.',
            ], 401);
        }

        // Kill old tokens
        $customer->tokens()->delete();

        $customer->update([
            'otp_verified'   => true,
            'otp'            => null,
            'otp_expires_at' => null,
        ]);

        $tokenName = $request->userAgent() ?? 'customer_app';

        $token = $customer->createToken(
            $tokenName,
            ['customer']
        )->plainTextToken;

        return response()->json([
            'status' => true,
            'message' => 'Login successful.',
            'token' => $token,
            'token_type' => 'Bearer',
            'customer' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'mobile' => $customer->mobile,
                'email' => $customer->email,
                'location' => $customer->location,
                'user_image' => $customer->user_image
                    ? asset('storage/' . $customer->user_image)
                    : null,
            ]
        ]);
    }


    /**
     * =========================
     * RESEND OTP OTP
     * =========================
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'mobile' => 'required|digits:10',
        ]);

        $customer = Customer::where('mobile', $request->mobile)->first();

        if (!$customer) {
            return response()->json([
                'status' => false,
                'message' => 'Customer not found.'
            ], 404);
        }

        if (!$customer->is_active) {
            return response()->json([
                'status' => false,
                'message' => 'Your account is inactive. Please contact support.'
            ], 403);
        }

        // Generate new OTP
        $otp = $this->generateOtp();

        $customer->update([
            'otp' => $otp,
            'otp_expires_at' => now()->addMinutes($this->otpExpiryMinutes),
        ]);

        return response()->json([
            'status' => true,
            'otp'  => $otp,
            'message' => 'OTP resent successfully.',
        ]);
    }
}
