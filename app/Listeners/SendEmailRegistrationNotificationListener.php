<?php

namespace App\Listeners;

use App\Notifications\RegisteredNotification;
use Illuminate\Auth\Events\Registered;

class SendEmailRegistrationNotificationListener
{
    public function __construct()
    {
    }

    public function handle(Registered $event): void
    {
        $event->user->notify(new RegisteredNotification());
    }
}
