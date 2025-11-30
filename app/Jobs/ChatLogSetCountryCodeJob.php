<?php

namespace App\Jobs;

use App\Models\ChatLog;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ChatLogSetCountryCodeJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(private readonly int $chatLogId, private readonly string $ip)
    {
    }

    public function handle(): void
    {
        $data = file_get_contents("http://ip-api.com/json/{$this->ip}?fields=status,countryCode,message");
        try {
            $data = json_decode($data, true, 512, JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            \Log::warning('Failed to get country code for IP from API: '.$this->ip, ['exception' => $e]);
        }

        if ($data['status'] !== 'success') {
            \Log::warning('Failed to get country code for IP: '.$this->ip, $data);

            return;
        }

        ChatLog::whereId($this->chatLogId)->update(['country_code' => $data['countryCode']]);
    }
}
