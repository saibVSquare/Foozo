<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe\Charge;
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
        $amount = $request->amount;
        $amount = $amount * 100;
        $paymentMethod = $request->payment_method;

        $user = auth()->user();
        $user->createOrGetStripeCustomer();

        if ($paymentMethod != null) {
            if ($paymentMethod instanceof \Laravel\Cashier\PaymentMethod) {
                $paymentMethod = $paymentMethod->id;
            }
            $user->addPaymentMethod($paymentMethod);
        }

        try {
            // Specify a return_url when creating the Payment Intent
            $paymentIntent = $user->createSetupIntent(['return_url' => route('payment.success')]);

            // Now, charge the user using the payment method ID and Payment Intent
            $user->charge($amount, $paymentMethod, [
                'payment_method' => $paymentMethod,
                'off_session' => true,
                'confirm' => true,
                'setup_future_usage' => 'off_session',
                'payment_method_types' => ['card'],
                'setup_intent' => $paymentIntent->id,
            ]);

            return redirect()->route('payment')->with('success', 'Payment successful!');
        } catch (\Exception $e) {
            return redirect()->route('payment')->with('error', $e->getMessage());
        }
    }

}
