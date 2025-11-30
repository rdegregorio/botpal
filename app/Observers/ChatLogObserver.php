<?php

namespace App\Observers;

use App\Models\ChatLog;

class ChatLogObserver
{
    public function creating(ChatLog $chatLog)
    {
        $chatLog->chat_uuid ??= \Str::uuid();
        $chatLog->message_uuid ??= \Str::uuid();
    }
}
