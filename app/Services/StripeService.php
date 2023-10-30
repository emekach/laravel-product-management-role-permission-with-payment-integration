<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Mail;
use App\Traits\ConsumesExternalServices;
use App\Notifications\PaymentConfirmationEmail;

class StripeService
{
    use ConsumesExternalServices;

    protected $baseUri;

    protected $key;

    protected $secret;
    protected $user;



    public function __construct(User $user)
    {
        $this->baseUri = config('services.stripe.base_uri');
        $this->key = config('services.stripe.key');
        $this->secret = config('services.stripe.secret');
        $this->user = $user;
    }

    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $headers['Authorization'] = $this->resolveAccessToken();
    }

    public function decodeResponse($response)
    {
        return json_decode($response);
    }

    public function resolveAccessToken()
    {
        return "Bearer {$this->secret}";
    }

    public function handlePayment(Request $request)
    {
        $request->validate([
            'payment_method' => 'required',
        ]);


        $intent = $this->createIntent($request->value, $request->currency, $request->payment_method);

        session()->put('paymentIntentId', $intent->id);
        // Get the authenticated user
        $user = auth()->user();

        // Include the user's roles in the response
        $roles = $user->getRoleNames();
        // Assign roles based on the product type
        $productType = $request->type; // You need to retrieve the product type from the request
        if ($productType === 'b2b') {
            // Assign 'b2b' role to the user
            $user->assignRole('b2b');
            $user->save();
        } elseif ($productType === 'b2c') {
            // Assign 'b2c' role to the user
            $user->assignRole('b2c');
            $user->save();
        }
        // return $user;
        return redirect('http://127.0.0.1:8000/api/payment/approval');
    }



    public function handleApproval()
    {
        if (session()->has('paymentIntentId')) {
            $paymentIntentId = session()->get('paymentIntentId');

            $confirmation = $this->confirmPayment($paymentIntentId);

            // Check if the payment was successful
            if ($confirmation->status === 'succeeded') {
                $latestChargeId = $confirmation->latest_charge;

                // Fetch the charge details
                $charge = $this->getChargeDetails($latestChargeId);

                // Send an email notification to the user
                // Inside the handleApproval method
                Mail::to(auth()->user()->email)->send(new PaymentConfirmationEmail($charge));

                // Access billing details from the charge
                $name = $charge->billing_details->name;
                $currency = strtoupper($confirmation->currency);
                $amount = $confirmation->amount / $this->resolveFactor($currency);

                return response()->json([
                    'payment' => "Thanks, {$name}. We received your {$amount}{$currency} payment."
                ], 200);
            }
        }

        return response()->json(['We are unable to confirm your payment. Try again, please'], 404);
    }

    public function getChargeDetails($chargeId)
    {
        return $this->makeRequest(
            'GET',
            "/v1/charges/{$chargeId}"
        );
    }


    public function createIntent($value, $currency, $paymentMethod)
    {


        return $this->makeRequest(
            'POST',
            '/v1/payment_intents',
            [],
            [
                'amount' => round($value * $this->resolveFactor($currency)),
                'currency' => 'usd',
                'payment_method' => $paymentMethod,
                'confirmation_method' => 'manual',

            ],
        );
    }

    public function confirmPayment($paymentIntentId)
    {
        return $this->makeRequest(
            'POST',
            "/v1/payment_intents/{$paymentIntentId}/confirm",
            [],
            [
                'return_url' => 'https:://google.com',
            ],
        );
    }


    public function resolveFactor($currency)
    {
        $zeroDecimalCureencies = ['JPY'];

        if (in_array(strtoupper($currency), $zeroDecimalCureencies)) {
            return 1;
        }
        return 100;
    }
}
