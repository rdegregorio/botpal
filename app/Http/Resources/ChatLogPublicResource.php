<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\ChatLog */
class ChatLogPublicResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
//            'id' => $this->id,
//            'chat_config_id' => $this->chat_config_id,
            'chat_uuid' => $this->chat_uuid,
            'message_uuid' => $this->message_uuid,
//            'question' => $this->question,
            'answer' => nl2br($this->answer),
//            'failed' => $this->failed,
//            'fail_reason' => $this->fail_reason,
//            'ip_address' => $this->ip_address,
//            'country_code' => $this->country_code,
//            'created_at' => $this->created_at,
//            'updated_at' => $this->updated_at,
//            'processed_at' => $this->processed_at,
            'processed' => $this->processed_at !== null,
//            'is_archived' => $this->is_archived,
//            'is_flagged' => $this->is_flagged,
//            'is_deleted' => $this->is_deleted,
//            'prompt_tokens' => $this->prompt_tokens,
//            'completion_tokens' => $this->completion_tokens,
//            'total_tokens' => $this->total_tokens,
        ];
    }
}
