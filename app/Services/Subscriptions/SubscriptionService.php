<?php

namespace App\Services\Subscriptions;

use App\Models\Subscription;
use App\Models\User;
use DomainException;
use Illuminate\Support\Collection;
use Stripe\Card;
use Stripe\Checkout\Session;
use Stripe\PaymentMethod;
use Stripe\Plan;
use Stripe\SetupIntent;
use Stripe\Stripe;

class SubscriptionService
{
    public const ERROR_MESSAGE_TOKEN_INACTIVE = 'Currently your API token is inactive. Please contact us for more information at https://stocknewsapi.com/contact - Thank you';
    public const ERROR_MESSAGE_USED_ALL_API_CALLS = 'Your user is currently inactive. Please login https://stocknewsapi.com/login and resume your subscription.';

    public static function hasLimits(User $user): bool
    {
        abort_if($user->isTokenDisabled(), 403, self::ERROR_MESSAGE_TOKEN_INACTIVE);

        /** @var Subscription $s */
        $s = $user->getCurrentActiveSubscription();

        if (!$s) {
            abort(403, self::ERROR_MESSAGE_USED_ALL_API_CALLS);
        }

        if (self::onTrialAndLimitsReached($s)) {

            abort(403,
                "You have used your free {$s->getAvailableTrialRequests()} trial calls. Please activate your subscription today.");
        }

        if ($s->expires_at->greaterThan(now())) {

            if ($s->left_requests_for_current_period <= 0 && !$s->extra_calls_left) {

                $errorMessage = 'API calls limit reached. For more calls upgrade to a higher plan or contact us at https://stocknewsapi.com/contact for a customized business plan.';
                $errorMessage .= "Next period starts: $s->expires_at ";
                $errorMessage .= '('.now()->timezoneName.')';

                abort(403, $errorMessage);
            } elseif ($s->left_requests_for_current_period <= 0 && $s->extra_calls_left) {
                $s->setUseExtraCalls(true);
            }

            return true;
        }

        self::refreshExpiresPeriod($s);

        return true;
    }

    public static function refreshExpiresPeriod(Subscription $s): void
    {
        $s->update([
            'requests_count' => 0,
            'expires_at' => optional($s->expires_at)->addMonth(),
        ]);
    }

    public static function getUserActiveSubscriptions(User $user): Collection
    {
        $subscriptions = collect();

        /**
         * @var Subscription $s
         */
        foreach ($user->subscriptions as $s) {

            /*
             * Skip expired stripe subscriptions
             */
            if (!$s->valid()) {
                continue;
            }

            $subscriptions->push($s);
        }

        return $subscriptions->sortByDesc('type');
    }

    public static function getUserActiveAutoPayableSubscription(User $user): ?Subscription
    {
        $subscriptions = self::getUserActiveSubscriptions($user);

        return $subscriptions->filter(function ($s) {
            /**
             * @var Subscription $s
             */
            return $s->isAutoPayable();
        })->first();
    }

    public static function incrementRequestsCounter(Subscription $subscription, int $increment = 1): void
    {
        $column = $subscription->onTrial() ? 'trial_requests_count' : 'requests_count';

        $subscription->increment($column, $increment);
    }

    public static function swap(User $user, int $type): Subscription
    {
        /*
         * Subscription name must be equal @var $planType
         */
        $planName = Subscription::getPlanNameByType($type);
        $activeSubscription = self::getUserActiveAutoPayableSubscription($user);

        throw_unless($activeSubscription, new DomainException('You don\'t have a subscription to change'));

        $user->subscription($activeSubscription->name)->swap($planName);

        $activeSubscription->update([
            'name' => $type,
            'type' => $type,
        ]);

        return $activeSubscription;
    }

    public static function changeCard(User $user, string $sessionId): void
    {
        Stripe::setApiKey(config('cashier.secret'));

        /** @var \Laravel\Cashier\PaymentMethod $oldPaymentMethod */
        $oldPaymentMethod = $user->paymentMethods()->first();

        $session = Session::retrieve($sessionId);
        $intent = SetupIntent::retrieve($session->setup_intent);
        $paymentMethod = PaymentMethod::retrieve($intent->payment_method);

        /** @var Card $card */
        $card = $paymentMethod->card;

        $user->card_brand = $card->brand;
        $user->card_last_four = $card->last4;
        $user->save();
        $user->updateDefaultPaymentMethod($paymentMethod);

        if ($oldPaymentMethod) {
            $oldPaymentMethod->delete();
        }
    }

    public static function updateUserContact(User $user): void
    {
        if (!$user->stripe_id) {
            return;
        }

        Stripe::setApiKey(config('cashier.secret'));

        $user->updateStripeCustomer([
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }

    public static function subscribeByCheckout(User $user, string $sessionId): Subscription
    {
        Stripe::setApiKey(config('cashier.secret'));
        $session = Session::retrieve($sessionId);

        $stripeSubscription = \Stripe\Subscription::retrieve($session->subscription);
        $paymentMethod = PaymentMethod::retrieve($stripeSubscription->default_payment_method);


        /** @var Plan $plan */
        $plan = $stripeSubscription->plan;
        $planName = $plan->id;
        $s = $user->getCurrentActiveSubscription();

        if (optional($s)->stripe_plan === $planName) {
            throw new DomainException('You already subscribed');
        }

        /** @var Card $card */
        $card = $paymentMethod->card;

        $user->stripe_id = $session->customer;
        $user->card_brand = $card->brand;
        $user->card_last_four = $card->last4;

        $planType = Subscription::getPlanTypeByName($planName);

        $s = new Subscription();
        $s->name = $planType;
        $s->stripe_plan = $planName;
        $s->stripe_id = $session->subscription;
        $s->quantity = 1;

        if ($user->can('subscribe-trial')) {
            $trialEndsAt = now()->addDays($user->getAvailableTrialDaysToSubscribe());
            $s->trial_ends_at = $trialEndsAt;
            $user->trial_ends_at = $trialEndsAt;
        }

        $user->save();
        $user->updateDefaultPaymentMethod($paymentMethod);

        return $user->subscriptions()->save($s);
    }


    public static function createSession(User $user, ?string $plan, bool $setupMode = false): Session
    {
        Stripe::setApiKey(config('cashier.secret'));

        $data = [
            'payment_method_types' => ['card'],
            'cancel_url' => route('pricing'),
        ];

        if ($setupMode) {
            $data['mode'] = 'setup';
            $data['success_url'] = route('account.checkout.change-card').'?session_id={CHECKOUT_SESSION_ID}';
        } else {
            $data['success_url'] = route('account.checkout.subscribe').'?session_id={CHECKOUT_SESSION_ID}';
            $data['subscription_data'] = [
                'items' => [
                    [
                        'plan' => $plan,
                    ]
                ],
            ];

            if ($user->can('subscribe-trial')) {
                $data['subscription_data']['trial_period_days'] = $user->getAvailableTrialDaysToSubscribe();
            }

            $subscription = $user->getCurrentActiveSubscription();

            if ($user->stripe_id && optional($subscription)->stripe_id) {
                $data['setup_intent_data'] = [
                    'metadata' => [
                        'customer_id' => $user->stripe_id,
                        'subscription_id' => $subscription->stripe_id,
                    ],
                ];
            }
        }

        return Session::create($data);
    }

    public static function createBusinessSubscription(User $user, $limit, $price): Subscription
    {
        $s = $user->subscriptions->filter(function ($s) {
            /**
             * @var Subscription $s
             */
            return $s->isBusiness();
        })->first();

        $s = $s ?? new Subscription();
        $s->name = Subscription::PLAN_BUSINESS;
        $s->type = Subscription::PLAN_BUSINESS;
        $s->custom_available_requests = $limit;
        $s->custom_price = $price;
        $user->subscriptions()->save($s);

        return $s;
    }

    public static function createFreeSubscription(User $user): Subscription
    {
        // Admin users get Premium plan automatically
        if ($user->isAdmin()) {
            return self::createPremiumSubscription($user);
        }

        $s = $user->subscriptions->filter(function ($s) {
            /**
             * @var Subscription $s
             */
            return $s->isFree();
        })->first();

        $s = $s ?? new Subscription();
        $s->name = Subscription::PLAN_FREE;
        $s->type = Subscription::PLAN_FREE;
        $s->custom_available_requests = 0;
        $s->custom_price = 0;
        $s->expires_at = now()->addMonth();
        $user->subscriptions()->save($s);

        return $s;
    }

    public static function createPremiumSubscription(User $user): Subscription
    {
        $s = $user->subscriptions->filter(function ($s) {
            /**
             * @var Subscription $s
             */
            return $s->type === Subscription::PLAN_PREMIUM;
        })->first();

        $s = $s ?? new Subscription();
        $s->name = Subscription::PLAN_PREMIUM;
        $s->type = Subscription::PLAN_PREMIUM;
        $s->custom_available_requests = 0;
        $s->custom_price = 0;
        $s->expires_at = now()->addYear(); // Admin gets 1 year
        $user->subscriptions()->save($s);

        return $s;
    }

    public static function removeCustomSubscription(User $user): void
    {
        $user->subscriptions->map(function ($s) {
            /**
             * @var Subscription $s
             */
            if (!$s->isBusiness()) {
                return;
            }

            $s->delete();
        });
    }

    public static function cancel(User $user): Subscription
    {
        $activeSubscription = self::getUserActiveAutoPayableSubscription($user);

        throw_unless($activeSubscription, new DomainException('Subscriptions not found'));

        $activeSubscription->canceled_at = now();
        $activeSubscription->save();

        return $activeSubscription->cancel();
    }

    public static function resume(User $user): Subscription
    {
        $activeSubscription = self::getUserActiveAutoPayableSubscription($user);

        throw_unless($activeSubscription, new DomainException('Subscriptions not found'));

        /*
         * Prevent to resume old $5 plan
         */
        throw_if($activeSubscription->stripe_plan === 'plan_Eov3Bi4i71ijnP',
            new DomainException('You can\'t resume this old plan. Please write to support or wait until it is over - '.$activeSubscription->ends_at));

        return $activeSubscription->resume();
    }

    public static function activate(User $user): Subscription
    {
        /** @var Subscription $s */
        $s = self::getUserActiveAutoPayableSubscription($user);

        throw_unless($s, new DomainException('Subscriptions not found'));

        Stripe::setApiKey(config('cashier.secret'));
        \Stripe\Subscription::update($s->stripe_id, ['trial_end' => 'now']);

        $date = now();
        $s->update(['trial_ends_at' => $date, 'expires_at' => now()->addMonth()]);

        if(!$user->trial_ends_at) {
            $user->update(['trial_ends_at' => $date]);
        }

        return $s;
    }

    public static function onTrialAndLimitsReached(Subscription $s): bool
    {
        return $s->left_requests_for_current_period <= 0 && $s->onTrial();
    }
}
