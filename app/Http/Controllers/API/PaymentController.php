<?php


namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Services\PayPalService;
use Illuminate\Http\Request;
use App\Resolvers\PaymentPlatformResolver;
use App\Services\StripeService;

class PaymentController extends Controller
{
    // protected $paymentPlatformResolver;

    protected $stripeService;

    public function __construct(StripeService $stripeService)
    {
        $this->stripeService = $stripeService;
    }

    // obtain data from payment form
    public function makePayment(Request $request)
    {
        $rules = [
            'value' => ['required', 'numeric', 'min:5'],
            'currency' => ['required', 'exists:currencies,iso'],

        ];

        $request->validate($rules);

        $paymentPlatform = resolve(StripeService::class);

        return $paymentPlatform->handlePayment($request);


        return $request->all();
    } // End Method


    public function approval()
    {
        $paymentPlatform = resolve(StripeService::class);



        return $paymentPlatform->handleApproval();


        return redirect()->route('dashboard')->withErrors('We cannot retrieve your payment platform, Try again');
    } //End Method
    public function cancelled()
    {
        return redirect()->route('dashboard')->withErrors('You cancelled the payment');
    } //End Method
}
