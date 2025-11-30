<?php

namespace App\Observers;

use App\Models\Subscription;

class SubscriptionObserver
{
    public function creating(Subscription $item)
    {
        $item->expires_at = $item->trial_ends_at ?? now()->addMonth();
        $item->quantity = 1;
        $item->trial_requests_count = 0;
        $item->stripe_plan = $item->stripe_plan ?? '';
        $item->stripe_id = $item->stripe_id ?? '';
        $item->stripe_status = $item->stripe_status ?? '';
        $item->type = $item->type ?? $item->name;
    }
}
