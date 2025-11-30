<?php

namespace App\Http\Requests;

use App\Rules\PaidUserRule;
use Illuminate\Foundation\Http\FormRequest;

class ChatConfigRequest extends FormRequest
{
    public function rules(): array
    {
        return [
//            'name' => ['required'],
            'general_prompt' => ['filled', 'string'],
            'welcome_message' => ['filled', 'string'],
            'character' => [
                'filled', function ($attribute, $value, $fail) {
                    if (is_numeric($value) && $value >= 1 && $value <= 12) {
                        return;
                    }

                    $uuid = $this->user()->chatConfigLatest?->uuid;

                    if (!preg_match("~^chat-characters/{$uuid}.(png|jpg|jpeg)\?[a-z0-9]{16}$~i", $value)) {
                        $fail('The character is invalid');
                    }
                }
            ],
            'q' => ['filled', 'array'],
            'a' => ['filled', 'array'],
            'settings' => ['sometimes', 'array'],
            'settings.'.\App\Models\ChatConfig::SETTINGS_FONT_FAMILY => [
                'sometimes', 'string', 'regex:/^[a-zA-Z\s-]+$/'
            ],
            'settings.'.\App\Models\ChatConfig::SETTINGS_FONT_SIZE => ['sometimes', 'int'],
            'settings.'.\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE => ['sometimes', 'int', 'in:70,80,90,100'],
            'settings.'.\App\Models\ChatConfig::SETTINGS_CHAT_PLACEMENT => ['sometimes', 'string', 'in:left,right'],
            'settings.'.\App\Models\ChatConfig::SETTINGS_COLOR_PRIMARY => ['sometimes', 'string', 'regex:/^#[a-zA-Z\d]+$/'],
            'settings.'.\App\Models\ChatConfig::SETTINGS_COLOR_SECONDARY => ['sometimes', 'string', 'regex:/^#[a-zA-Z\d]+$/'],
            'settings.'.\App\Models\ChatConfig::SETTINGS_COLOR_CHARACTER_BG => ['sometimes', 'string', 'regex:/^#[a-zA-Z\d]+$/'],
            'settings.'.\App\Models\ChatConfig::SETTINGS_COPYRIGHT_ENABLED => ['sometimes', 'bool', new PaidUserRule()],
//            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,txt'],
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('settings') && is_array($this->settings)) {
            $allowedSettingsKeys = [
                \App\Models\ChatConfig::SETTINGS_FONT_FAMILY,
                \App\Models\ChatConfig::SETTINGS_FONT_SIZE,
                \App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE,
                \App\Models\ChatConfig::SETTINGS_CHAT_PLACEMENT,
                \App\Models\ChatConfig::SETTINGS_COLOR_PRIMARY,
                \App\Models\ChatConfig::SETTINGS_COLOR_SECONDARY,
                \App\Models\ChatConfig::SETTINGS_COLOR_CHARACTER_BG,
                \App\Models\ChatConfig::SETTINGS_COPYRIGHT_ENABLED,
            ];

            $this->merge([
                'settings' => array_intersect_key($this->settings, array_flip($allowedSettingsKeys))
            ]);
        }
    }


    public function messages(): array
    {
        return [
            'general_prompt.filled' => 'Please give a summary for the service you provide.',
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
