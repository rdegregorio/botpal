<?php

namespace App\Services\Subscriptions;

use App\Models\Subscription;
use Laravel\Cashier\Billable;

trait CustomBillable
{
    use Billable;

    /**
     * Override the subscriptions() from Laravel\Cashier\Billable
     * to inject CustomSubscription model
     */
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, $this->getForeignKey())->orderBy('created_at', 'desc');
    }
}