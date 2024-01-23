<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProductController extends Controller
{
    public function show()
    {
        $user = Auth::user();
        $paymentIntent = $user->createSetupIntent();
        return view('product.show', [
            'paymentIntent' => $paymentIntent,
            'stripePublicKey' => config('cashier.key'),
        ]);
    }

    public function processPayment(Request $request)
    {
        $user = Auth::user();
        $paymentMethod = $request->input('payment_method');
        $user->createOrGetStripeCustomer();
        $paymentMethod = $user->addPaymentMethod($paymentMethod);
        $user->charge(200, $paymentMethod->id);
        return redirect()->route('payment')->with('success', 'Payment successful!');
    }

}
