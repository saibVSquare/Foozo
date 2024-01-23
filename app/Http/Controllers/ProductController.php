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
        
        // $user->updateDefaultPaymentMethod($paymentMethod);
        // dd($test);
        // $setupIntent = $user->setupIntent();
        // $paymentIntent = $user->confirmPaymentIntent($setupIntent->id);
        $paymentIntent = $user->charge(100, $paymentMethod, [
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never',
            ]
        ]);
        // Handle successful payment
        return redirect()->route('payment')->with('success', 'Payment successful!');
    }

}
