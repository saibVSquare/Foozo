<?php

namespace App\Http\Controllers;

use App\Models\Plan;
use Exception;
use Illuminate\Http\Request;
use Laravel\Cashier\Subscription;

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
            'interval_count' => $request->interval_count,
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
            'interval_count' => $strip_plan->interval_count
        ]);

        return "Strip Plan is created in the Stripe Dashboard and Local Database";
    }

    public function plans()
    {
        $plans = Plan::all();
        return view('stripe.plans.index', compact('plans'));
    }

    public function plansCheckout($plan_id)
    {
        $plan = Plan::where('plan_id', $plan_id)->first();
        if ($plan) {
            return view(
                'stripe.plans.checkout',
                [
                    'plan' => $plan,
                    'intent' => auth()->user()->createSetupIntent()
                ]
            );
        }
    }

    public function planProcess(Request $request)
    {
        $user = auth()->user();
        $user->createOrGetStripeCustomer();
        $paymentMethod = null;
        $paymentMethod = $request->payment_method;
        if ($paymentMethod != null) {
            $paymentMethod = $user->addPaymentMethod($paymentMethod);
        }
        $plan = $request->plan_id;

        try {
            $user->newSubscription(
                'default',
                $plan
            )->create($paymentMethod != null ? $paymentMethod->id : '');
        } catch (Exception $ex) {
            return back()->withErrors([
                'error' => 'Unable to create subscription due to this issue ' . $ex->getMessage()
            ]);
        }

        $request->session()->flash('alert-success', 'You are subscribed to this plan');
        return to_route('plan.checkout', $plan);
    }

    public function subscription()
    {
        $subscriptions = Subscription::where('user_id', auth()->user()->id)->get();
        // dd($subscriptions);
        return view('stripe.subscriptions.index', compact('subscriptions'));
    }


}
