<?php

namespace App\Observers;

use App\Models\ChatConfig;

class ChatConfigObserver
{
    public function creating(ChatConfig $chatConfig): void
    {
        $chatConfig->name = 'Main';
        $chatConfig->uuid = \Str::uuid();
        $chatConfig->character ??= 3; // Ben is the default
    }
}
