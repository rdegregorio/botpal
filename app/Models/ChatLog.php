<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChatLog extends Model
{
    protected $guarded = ['id'];

    protected $casts = [
        'is_archived' => 'boolean',
        'is_flagged' => 'boolean',
        'is_deleted' => 'boolean',
        'source_documents' => 'array',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function scopeRegular(Builder $query): Builder
    {
        return $query->where([
            'is_archived' => false,
            'is_deleted' => false,
            'is_flagged' => false,
        ]);
    }

    public function chatConfig(): BelongsTo
    {
        return $this->belongsTo(ChatConfig::class);
    }

    public function isProcessed(): bool
    {
        return $this->processed_at !== null;
    }
}
