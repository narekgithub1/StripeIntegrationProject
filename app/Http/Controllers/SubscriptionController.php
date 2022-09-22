<?php

namespace App\Http\Controllers;

use App\Models\SubscriptionItems;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Subscription;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{
    public function index()
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));
        $plans = $stripe->plans->all()->toArray();
        $data = $plans['data'];
        $arr = [];
        foreach ($data as $item) {
            $a = ($stripe->products->retrieve(
                $item['product']
            ));

            $item['name'] = $a['name'];
            array_push($arr, $item);
        }
        $stripeId = Auth::user()->stripe_id;

        $planStripeName = Subscription::where('stripe_id',$stripeId)->first();
        return view('subscribe', ['plans' => $arr ,'planStripeName' => $planStripeName->name , 'planStatus' => $planStripeName->stripe_status]);

    }

    public function cancelSubscription(Request $request)
    {
        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $subscriptions = $stripe->subscriptions->all();

        $arr = [];

        foreach ($subscriptions->data as $item) {
            if ($item->plan->id === $request->id) {
                array_push($arr, $item);
            }
        }

        $stripe->subscriptions->cancel(
            $arr[0]->id,
            []
        );
    }

    public function store(Request $request)
    {
//      dd($request->all());

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        \Stripe\Stripe::setApiKey(config('services.stripe.secret'));

        $token = $_POST['stripeToken'];

        $customer = \Stripe\Customer::create(array(
            'email' => $request->email,
            'source' => $token,
        ));

        $stripe->subscriptions->create([
            'customer' => $customer->id,
            'items' => [
                ['price' => $request['plan_id']],
            ],
        ]);

//        \Stripe\Charge::create(array(
//            'amount' => $request['amount'],
//            'currency' => $request['currency'],
//            'customer' => $customer->id
//        ));

        $intents = $stripe->paymentIntents->create([
            'customer' => $customer->id,
            'amount' => $request->amount,
            'currency' => $request->currency,
            'payment_method_types' => ['card'],
            'confirmation_method' =>'manual',
            'confirm' => true,
        ]);

//        dd($intents);
        if ($intents->next_action){
            Log::info("!!!!!!1");
            if ($intents->next_action->use_stripe_sdk->stripe_js){
                $url = $intents->next_action->use_stripe_sdk->stripe_js;
                Log::info("!!!!!!2222 ".$url);
                return Redirect::away($url);
            }
        }
//
//        $confirmPaymentIntents = $stripe->paymentIntents->confirm(
//            $intents->id,
//            ['payment_method' => 'pm_card_visa'],
//        );

//dd($confirmPaymentIntents);



//        dd($confirmPaymentIntents);
//        $receipt_url = $confirmPaymentIntents->charges->data[0]->receipt_url;
//        dd($confirmPaymentIntents->charges->data[0]);
/// status


        $setupIntents = $stripe->setupIntents->create([
            'customer' => $customer->id,
            'payment_method_types' => ['card'],
        ]);

//        dd($setupIntents);


        $confirmSetupIntents = $stripe->setupIntents->confirm(
            $setupIntents->id,
            ['payment_method' => 'pm_card_visa']
        );

//        dd($confirmSetupIntents);

//        \Stripe\Charge::create(array(
//            'amount' => $request['amount'],
//            'currency' => $request['currency'],
//            'customer' => $customer->id
//        ));

//
        return $this->index($intents);
    }

    public function webhook(Request $request)
    {
        $data = ($request->data['object']);

        $stripe = new \Stripe\StripeClient(config('services.stripe.secret'));

        $user = $stripe->customers->retrieve(
            $data['customer'],
            []
        );
        $authUser = User::where('email', $user->email)->first();

        $currentPlan = Subscription::where('user_id', $authUser->id);

        if ($request->type === 'customer.subscription.created') {
            if ($currentPlan->count() > 0) {
                Subscription::where('user_id', $authUser->id)->update([
                    "name" => $data['plan']['id'],
                    'stripe_id' => $data['id'],
                    'stripe_price' => $data['plan']['amount'],
                    'quantity' => $data['quantity'],
                    'stripe_status' => $data['status'],
                    'trial_ends_at' => $data['trial_end'],
                    'ends_at' => $data['ended_at'],
                ]);
            } else {
                $dataItem = [
                    "name" => $data['plan']['id'],
                    'user_id' => $authUser ? $authUser->id : null,
                    'stripe_id' => $data['id'],
                    'stripe_price' => $data['plan']['amount'],
                    'quantity' => $data['quantity'],
                    'stripe_status' => $data['status'],
                    'trial_ends_at' => $data['trial_end'],
                    'ends_at' => $data['ended_at'],
                ];
                Subscription::create($dataItem);
            }

            User::where('email', $user->email)->update([
                'stripe_id' => $data['id'],
            ]);

            $lastSubscription = Subscription::where('stripe_id', $data['id'])->get();

            $currentSubscriptionItem = SubscriptionItems::where('subscription_id', $lastSubscription[0]->id);
            if ($lastSubscription &&  ($currentSubscriptionItem->count() === 0)) {
                $subscriptionItem = [
                    "subscription_id" => $lastSubscription[0]->id,
                    'stripe_id' => $data['id'],
                    'stripe_product' => $data['plan']['product'],
                    'stripe_price' => $data['plan']['amount'],
                    'quantity' => $data['quantity'],
                ];

                SubscriptionItems::create($subscriptionItem);
            } else {
                SubscriptionItems::where('subscription_id',  $lastSubscription[0]->id)->update([
                    "subscription_id" => $lastSubscription[0]->id,
                    'stripe_id' => $data['id'],
                    'stripe_product' => $data['plan']['product'],
                    'stripe_price' => $data['plan']['amount'],
                    'quantity' => $data['quantity'],
                ]);
            }

        }

        if ($request->type === 'customer.subscription.deleted') {

            Subscription::where('stripe_id', $data['id'])->update([
                'stripe_status' => $data['status'],
            ]);

            User::where('email', $user->email)->update([
                'stripe_id' => null,
            ]);
        }


        if ($request->type === 'customer.subscription.updated') {
            Subscription::where('stripe_id', $data['id'])->update([
                "name" => $request->data->customer,
                'stripe_id' => $data['id'],
                'stripe_price' => $data['plan']['amount'],
                'quantity' => $data['quantity'],
                'stripe_status' => $data['status'],
                'trial_ends_at' => $data['trial_end'],
                'ends_at' => $data['ended_at'],
            ]);
        }

        Log::info('weebhook999999999999' . ($request));
    }
}
