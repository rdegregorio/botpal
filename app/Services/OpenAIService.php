<?php

namespace App\Services;


use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

class OpenAIService
{
    public const CHAT_LOG = 'chat_log';

    public const MODEL_35_TURBO = 'gpt-4o-mini'; // Fast and affordable
    public const MODEL_4_PREVIEW = 'gpt-4o'; // Most capable model
    public const AVAILABLE_MODELS = [
        self::MODEL_35_TURBO,
        self::MODEL_4_PREVIEW,
    ];

    private $apiKey;
    private $model;

    public function getConfig(string $option): array
    {
        $baseConfig = [
            'max_tokens' => 1000,
            'temperature' => 0,
            'model' => $this->model,
            'top_p' => 1.0,
            'frequency_penalty' => 0.0,
            'presence_penalty' => 0.0,
            'url' => 'https://api.openai.com/v1/chat/completions',
            'conversation' => [],
        ];

        $configs = [
            self::CHAT_LOG => [
                'model' => $this->model,
                'system_prompt' => '',
                'prompt' => '',
                'max_tokens' => 5000,
                'temperature' => 1,
            ],
        ];

        $config = $configs[$option] ?? null;

        throw_unless($config, new \InvalidArgumentException('Wrong option value'));

        return array_merge($baseConfig, $config);
    }

    public function __construct(string $apiKey, string $model)
    {
        $this->apiKey = $apiKey;
        $this->model = $model;
    }

    public static function validateToken(string $token): bool
    {
        $client = new Client([
            'timeout' => 115,
            'verify' => false,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$token
        ];

        $request = new Request('GET', 'https://api.openai.com/v1/engines', $headers);
        try {
            $response = $client->sendAsync($request)->wait();
        } catch (\Exception $e) {
            return false;
        }

        return $response->getStatusCode() === 200;
    }

    public function requestGptTurbo(array $config): array
    {
        $client = new Client([
            'timeout' => 115,
            'verify' => false,
        ]);

        $headers = [
            'Content-Type' => 'application/json',
            'Authorization' => 'Bearer '.$this->apiKey
        ];

        $config['prompt'] = self::sanitizeText($config['prompt']);
        $config['system_prompt'] = self::sanitizeText($config['system_prompt']);

        $messages = [
            [
                'role' => 'system',
                'content' => $config['system_prompt'],
            ]
        ];

        foreach ($config['conversation'] as $item) {
            $messages[] = [
                'role' => $item['role'],
                'content' => self::sanitizeText($item['content']),
            ];
        }

        $messages[] = [
            'role' => 'user',
            'content' => $config['prompt'],
        ];

        $body = json_encode([
            'model' => $config['model'],
            'messages' => $messages,
        ], JSON_THROW_ON_ERROR);

        $request = new Request('POST', 'https://api.openai.com/v1/chat/completions', $headers, $body);
        $response = $client->sendAsync($request)->wait();

        $body = $response->getBody()->getContents();

//        \Log::channel('openai')->info($body, $config);

        return json_decode($body, true);
    }

    public static function sanitizeText(string $text): string
    {
        $search = ['"', "\n", '“', '”', '’', "\t", "\r"];
        $replace = ['\"', '\n', '"', '"', '\'', '\t', '\r'];

        return str_replace($search, $replace, $text);
    }
}
