<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RegisteredNotification extends Notification
{
    public function __construct()
    {
    }

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Welcome to Iamsam.ai - Get Started')
            ->markdown('mails.notifications.register', ['name' => $notifiable->name]);
    }

    public function toArray($notifiable): array
    {
        return [];
    }
}
