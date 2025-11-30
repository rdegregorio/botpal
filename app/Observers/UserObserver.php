<?php

namespace App\Observers;

use App\Models\User;
use App\Services\Subscriptions\SubscriptionService;
use App\Services\UserService;

class UserObserver
{
    public function saved(User $user)
    {
        if($user->isDirty(['email', 'name'])) {
            SubscriptionService::updateUserContact($user);
        }
    }
}
