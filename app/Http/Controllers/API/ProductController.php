<?php

namespace App\Http\Controllers\API;

use App\Models\User;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class ProductController extends Controller
{

    public function index()
    {
        $products = Product::all();

        if (!$products->isEmpty()) {
            return response()->json([
                'product' => $products
            ], 200);
        }

        return response()->json([
            'error' => 'No Product Available for display'
        ]);
    }

    public function show($id)
    {
        $product = Product::find($id);

        if (!$product) {
            return response()->json(['message' => 'Product not found'], 404);
        }


        return response()->json([
            'single_product' => $product
        ], 200);
    }


    public function purchase(Request $request)
    {
        try {
            // Create a new user or retrieve an existing one based on the email
            $user = User::firstOrCreate(
                ['email' => $request->input('email')],
                [
                    'password' => Hash::make(Str::random(12)),
                    'name' => $request->input('first_name') . ' ' . $request->input('last_name'),
                    'address' => $request->input('address'),
                    'city' => $request->input('city'),
                    'state' => $request->input('state'),
                    'zip_code' => $request->input('zip_code')
                ]
            );

            // Create or retrieve the Stripe customer for the user
            $user->createOrGetStripeCustomer();

            // Charge the user's payment method
            $payment = $user->charge(
                $request->input('amount') * 100, // Amount in cents
                $request->input('payment_method_id')
            );

            // Record the transaction in your database
            $order = $user->transactions()->create([
                'order_id' => $payment->id,
                'amount' => $payment->amount,
            ]);

            // Load additional data if needed
            $order->load('products');

            return response()->json([
                'message' => 'Payment successful',
                'order' => $order,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => $e->getMessage()
            ], 500);
        }
    }
}
