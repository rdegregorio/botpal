<?php

namespace App\Http\Controllers;

use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class StripeController extends Controller
{
    /**
     * Create Stripe Checkout session for Premium subscription
     */
    public function checkout(Request $request)
    {
        $user = Auth::user();
        $priceId = config('services.stripe.price_premium');
        $secretKey = config('services.stripe.secret');

        if (!$priceId || !$secretKey) {
            return redirect()->back()->with('error', 'Stripe is not configured properly.');
        }

        // Create Stripe Checkout Session via API
        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions');

        $postData = http_build_query([
            'payment_method_types[]' => 'card',
            'line_items[0][price]' => $priceId,
            'line_items[0][quantity]' => 1,
            'mode' => 'subscription',
            'success_url' => route('stripe.success') . '?session_id={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('stripe.cancel'),
            'customer_email' => $user->email,
            'metadata[user_id]' => $user->id,
            'subscription_data[metadata][user_id]' => $user->id,
        ]);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $secretKey,
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || !isset($data['url'])) {
            Log::error('Stripe checkout failed', ['response' => $data]);
            return redirect()->back()->with('error', 'Failed to create checkout session. Please try again.');
        }

        return redirect($data['url']);
    }

    /**
     * Handle successful payment return
     */
    public function success(Request $request)
    {
        $sessionId = $request->get('session_id');
        $secretKey = config('services.stripe.secret');

        if (!$sessionId) {
            return redirect()->route('dashboard')->with('error', 'Invalid session.');
        }

        // Retrieve the checkout session from Stripe
        $ch = curl_init('https://api.stripe.com/v1/checkout/sessions/' . $sessionId . '?expand[]=subscription');

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $secretKey,
            ],
        ]);

        $response = curl_exec($ch);
        curl_close($ch);

        $session = json_decode($response, true);

        if (!isset($session['subscription'])) {
            return redirect()->route('dashboard')->with('error', 'Subscription not found.');
        }

        $subscriptionData = $session['subscription'];
        $userId = $session['metadata']['user_id'] ?? Auth::id();
        $customerId = $session['customer'] ?? null;

        // Update user with Stripe customer ID
        if ($customerId) {
            $user = User::find($userId);
            if ($user) {
                $user->stripe_id = $customerId;
                $user->save();
            }
        }

        // Check if user had any previous subscriptions (to determine if new or upgrade)
        $hadPreviousSubscription = Subscription::where('user_id', $userId)->exists();

        // Create or update subscription
        $this->createOrUpdateSubscription($userId, $subscriptionData);

        if (!$hadPreviousSubscription) {
            return redirect()->route('knowledge')->with('success', 'Welcome to Premium! Let\'s set up your chatbot knowledge base.');
        }

        return redirect()->route('dashboard')->with('success', 'Upgraded to Premium! Your subscription is now active.');
    }

    /**
     * Handle cancelled payment
     */
    public function cancel()
    {
        return redirect()->route('select-plan')->with('info', 'Payment was cancelled. You can try again anytime.');
    }

    /**
     * Redirect to Stripe Customer Portal for managing payment methods
     */
    public function portal()
    {
        $user = Auth::user();
        $secretKey = config('services.stripe.secret');

        if (!$user->stripe_id) {
            return redirect()->route('account.billing')->with('error', 'No Stripe customer found. Please subscribe first.');
        }

        // Create Stripe Customer Portal session
        $ch = curl_init('https://api.stripe.com/v1/billing_portal/sessions');

        $postData = http_build_query([
            'customer' => $user->stripe_id,
            'return_url' => route('account.billing'),
        ]);

        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $postData,
            CURLOPT_HTTPHEADER => [
                'Authorization: Bearer ' . $secretKey,
                'Content-Type: application/x-www-form-urlencoded',
            ],
        ]);

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        $data = json_decode($response, true);

        if ($httpCode !== 200 || !isset($data['url'])) {
            Log::error('Stripe portal creation failed', ['response' => $data]);
            return redirect()->route('account.billing')->with('error', 'Unable to access billing portal. Please try again.');
        }

        return redirect($data['url']);
    }

    /**
     * Handle Stripe webhook events
     */
    public function webhook(Request $request)
    {
        $payload = $request->getContent();
        $sigHeader = $request->header('Stripe-Signature');
        $webhookSecret = config('services.stripe.webhook_secret');

        // Verify webhook signature if secret is configured
        if ($webhookSecret) {
            $timestamp = null;
            $signature = null;

            foreach (explode(',', $sigHeader) as $part) {
                [$key, $value] = explode('=', $part, 2);
                if ($key === 't') $timestamp = $value;
                if ($key === 'v1') $signature = $value;
            }

            $expectedSignature = hash_hmac('sha256', $timestamp . '.' . $payload, $webhookSecret);

            if (!hash_equals($expectedSignature, $signature)) {
                Log::warning('Stripe webhook signature verification failed');
                return response('Invalid signature', 400);
            }
        }

        $event = json_decode($payload, true);

        Log::info('Stripe webhook received', ['type' => $event['type']]);

        switch ($event['type']) {
            case 'customer.subscription.created':
            case 'customer.subscription.updated':
                $this->handleSubscriptionUpdated($event['data']['object']);
                break;

            case 'customer.subscription.deleted':
                $this->handleSubscriptionCancelled($event['data']['object']);
                break;

            case 'invoice.payment_succeeded':
                $this->handlePaymentSucceeded($event['data']['object']);
                break;

            case 'invoice.payment_failed':
                $this->handlePaymentFailed($event['data']['object']);
                break;
        }

        return response('OK', 200);
    }

    /**
     * Create or update subscription record
     */
    private function createOrUpdateSubscription(int $userId, array $subscriptionData): void
    {
        $stripeId = is_array($subscriptionData) ? ($subscriptionData['id'] ?? null) : $subscriptionData;

        // End any existing active subscriptions
        Subscription::where('user_id', $userId)
            ->whereNull('ends_at')
            ->update(['ends_at' => now()]);

        // Create new Premium subscription
        Subscription::create([
            'user_id' => $userId,
            'type' => Subscription::TYPE_PREMIUM,
            'stripe_id' => $stripeId,
            'stripe_status' => 'active',
            'requests_limit' => 10000, // 10,000 messages/month for Premium
            'requests_used' => 0,
        ]);

        Log::info('Premium subscription created', ['user_id' => $userId, 'stripe_id' => $stripeId]);
    }

    /**
     * Handle subscription updated webhook
     */
    private function handleSubscriptionUpdated(array $subscription): void
    {
        $userId = $subscription['metadata']['user_id'] ?? null;

        if (!$userId) {
            Log::warning('No user_id in subscription metadata', ['subscription_id' => $subscription['id']]);
            return;
        }

        $sub = Subscription::where('stripe_id', $subscription['id'])->first();

        if ($sub) {
            $sub->update([
                'stripe_status' => $subscription['status'],
            ]);
        } else {
            $this->createOrUpdateSubscription($userId, $subscription);
        }
    }

    /**
     * Handle subscription cancelled webhook
     */
    private function handleSubscriptionCancelled(array $subscription): void
    {
        $sub = Subscription::where('stripe_id', $subscription['id'])->first();

        if ($sub) {
            $sub->update([
                'stripe_status' => 'canceled',
                'ends_at' => now(),
            ]);

            Log::info('Subscription cancelled', ['subscription_id' => $subscription['id']]);
        }
    }

    /**
     * Handle successful payment webhook
     */
    private function handlePaymentSucceeded(array $invoice): void
    {
        $subscriptionId = $invoice['subscription'] ?? null;

        if (!$subscriptionId) {
            return;
        }

        $sub = Subscription::where('stripe_id', $subscriptionId)->first();

        if ($sub) {
            // Reset monthly usage on successful payment (new billing cycle)
            $sub->update([
                'stripe_status' => 'active',
                'requests_used' => 0,
            ]);

            Log::info('Payment succeeded, usage reset', ['subscription_id' => $subscriptionId]);
        }
    }

    /**
     * Handle failed payment webhook
     */
    private function handlePaymentFailed(array $invoice): void
    {
        $subscriptionId = $invoice['subscription'] ?? null;

        if (!$subscriptionId) {
            return;
        }

        $sub = Subscription::where('stripe_id', $subscriptionId)->first();

        if ($sub) {
            $sub->update([
                'stripe_status' => 'past_due',
            ]);

            Log::warning('Payment failed', ['subscription_id' => $subscriptionId]);
        }
    }
}
