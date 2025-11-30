<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Auth;

class ChatConfig extends Model
{
    public const TYPE_PDF = 'pdf';
    public const TYPE_PLAIN_TEXT = 'plainText';
    public const TYPE_FAQ = 'faq';
    public const SETTINGS_FONT_FAMILY = 'font_family';
    public const SETTINGS_FONT_SIZE = 'font_size';
    public const SETTINGS_CHARACTER_SIZE = 'character_size';
    public const SETTINGS_CHAT_PLACEMENT = 'chat_placement';
    public const SETTINGS_COLOR_PRIMARY = 'color_primary';
    public const SETTINGS_COLOR_SECONDARY = 'color_secondary';
    public const SETTINGS_COLOR_CHARACTER_BG = 'color_character_bg';
    public const SETTINGS_COPYRIGHT_ENABLED = 'copyright_enabled';
    public const SETTINGS_AI_MODEL = 'ai_model';

    public const TYPES
        = [
            self::TYPE_PDF,
            self::TYPE_PLAIN_TEXT,
            self::TYPE_FAQ,
        ];

    public const DEFAULT_COLOR = '#D5103E';

    protected $fillable
        = [
            'name',
            'general_prompt',
            'user_id',
            'items',
            'color',
            'character',
            'welcome_message',
            'settings',
        ];

    protected $casts
        = [
            'items' => 'array',
            'settings' => 'array',
        ];

    public static function prepareItems(array $q, array $a): array
    {
        $items = [];

        foreach ($q as $k => $item) {
            $answer = $a[$k] ?? null;
            if (!$answer) {
                continue;
            }
            $items[] = [
                'q' => $item,
                'a' => $answer,
            ];
        }

        return $items;
    }

    public function hasLimits()
    {
        return true; // because we use users tokens
        $s = $this->user->getCurrentActiveSubscription();

        if (!$s) {
            return false;
        }

        return $s->left_requests_for_current_period > 0;
    }

    protected function items(): Attribute
    {
        return Attribute::make(
            get: static fn($value, array $attributes) => json_decode($value ?? '[]', true, 512, JSON_THROW_ON_ERROR) ?? [],
        );
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatLog::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        // todo implement logic
        return $query;
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getCharacterAttribute($val)
    {
        return $val ?? 3;
    }

    public function getTypeAttribute($val)
    {
        return $this->user?->getCurrentActiveSubscription()?->isFree() ? self::TYPE_FAQ : $val;
    }

    public function getCharacterUrlAttribute()
    {
        if (is_numeric($this->character)) {
            return url("/images/ch{$this->character}.png");
        }

        if ($this->character) {
            return url('storage/'.$this->character);
        }

        return url('/images/ch3.png');
    }

    public function getSettings(string $key)
    {
        return match ($key) {
            self::SETTINGS_FONT_FAMILY => $this->settings[self::SETTINGS_FONT_FAMILY] ?? 'Tahoma',
            self::SETTINGS_FONT_SIZE => $this->settings[self::SETTINGS_FONT_SIZE] ?? 15,
            self::SETTINGS_CHARACTER_SIZE => $this->settings[self::SETTINGS_CHARACTER_SIZE] ?? 80,
            self::SETTINGS_CHAT_PLACEMENT => $this->settings[self::SETTINGS_CHAT_PLACEMENT] ?? 'right',
            self::SETTINGS_COLOR_PRIMARY => $this->settings[self::SETTINGS_COLOR_PRIMARY] ?? '#D4103E',
            self::SETTINGS_COLOR_SECONDARY => $this->settings[self::SETTINGS_COLOR_SECONDARY] ?? '#E4E3E8',
            self::SETTINGS_COLOR_CHARACTER_BG => $this->settings[self::SETTINGS_COLOR_CHARACTER_BG] ?? '#ffffff',
            self::SETTINGS_COPYRIGHT_ENABLED => $this->settings[self::SETTINGS_COPYRIGHT_ENABLED] ?? true,
            default => $this->settings[$key] ?? null,
        };
    }

    public function getContext(): ?string
    {
        $filePath = storage_path("app/chat-configs/{$this->id}/{$this->type}/$this->type.txt");

        if (is_file($filePath)) {
            return file_get_contents($filePath);
        }

        return null;
    }
}
