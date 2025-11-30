<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ChatLog */
class ChatLogDashboardResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
//            'id' => $this->id,
//            'chat_config_id' => $this->chat_config_id,
            'message_uuid' => $this->message_uuid,
//            'chat_uuid' => $this->chat_uuid,
            'question' => $this->question,
            'answer' => nl2br($this->answer),
//            'failed' => $this->failed,
//            'fail_reason' => $this->fail_reason,
            'ip_address' => $this->ip_address,
            'country_code' => $this->country_code,
            'created_at' => $this->created_at->toDateTimeString(),
//            'updated_at' => $this->updated_at,
//            'processed_at' => $this->processed_at,
            'is_archived' => $this->is_archived,
            'is_flagged' => $this->is_flagged,
            'is_deleted' => $this->is_deleted,
        ];
    }
}
