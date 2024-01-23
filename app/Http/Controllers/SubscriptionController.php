<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Illuminate\Http\Request;

class SubscriptionController extends Controller
{
    public function planCreate()
    {
        return view('stripe.plans.create');
    }

    public function planStore(Request $request)
    {
        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));

        $amount = $request->amount * 100;
        $strip_plan = \Stripe\Plan::create([
            'amount' => $amount,
            'currency' => 'usd',
            'interval' => $request->billing_period,
            'interval_count'=> $request->interval_count,
            'product' => [
                'name' => $request->name
            ]
        ]);

        Plan::create([
            'plan_id' => $strip_plan->id,
            'name' => $request->name,
            'price' => $strip_plan->amount,
            'billing_method' => $strip_plan->interval,
            'currency' => $strip_plan->currency,
            'interval_count'=> $strip_plan->interval_count
        ]);

        return "Strip Plan is created in the Stripe Dashboard and Local Database";
    }
}
