<?php

namespace App\Jobs;

use App\Models\ChatLog;
use App\Services\OpenAIService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessChatRequestJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $chatLog;

    public function __construct(ChatLog $chatLog)
    {
        $this->chatLog = $chatLog;
        $this->onQueue('chat');
    }

    public function handle(): void
    {
        if ($this->chatLog->processed_at) {
            return;
        }

        // Use shared API key and default model from config
        $apiKey = config('services.openai.api_key');
        $model = config('services.openai.default_model', 'gpt-5-mini');

        if (!$apiKey) {
            \Log::channel('openai')->info("OpenAI API key not configured");
            return;
        }

        $service = new OpenAIService($apiKey, $model);

        $chatConfig = $this->chatLog->chatConfig;

        $systemPrompt = $chatConfig->general_prompt;
        $systemPrompt .= "\n\n$chatConfig->welcome_message\n\nEnsure to avoid answering questions that are unrelated to the service.";

        $context = $chatConfig->getContext();

        if($context) {
            $systemPrompt .= "\n\nContext: ".$context;
        } else {
            \Log::channel('openai')->info("File not found for config ID: {$chatConfig->id}");
        }

        $history = ChatLog::whereChatUuid($this->chatLog->chat_uuid)
            ->where('id', '<>', $this->chatLog->id)
            ->orderBy('id')
            ->get();

        $config = $service->getConfig(OpenAIService::CHAT_LOG);

        $config['system_prompt'] = $systemPrompt;

        $config['prompt'] = $this->chatLog->question;

        if($history->isNotEmpty()) {
            $history->each(function (ChatLog $chatLog) use (&$config) {
                $config['conversation'][] = [
                    'role' => 'user',
                    'content' => $chatLog->question,
                ];
                $config['conversation'][] = [
                    'role' => 'assistant',
                    'content' => $chatLog->answer,
                ];
            });
        }

        try {
            $response = $service->requestGptTurbo($config);
            $text = trim($response['choices'][0]['message']['content'], " \t\n\r\0\x0B\"");
            $data = [
                'answer' => $text,
                'prompt_tokens' => $response['usage']['prompt_tokens'],
                'completion_tokens' => $response['usage']['completion_tokens'],
            ];
        } catch (\Exception $e) {
            $userData = $chatConfig->user->only(['id', 'name', 'email']);
            $logContext = \Arr::except($config, ['conversation', 'prompt', 'system_prompt']);
            \Log::channel('openai')->info('[Chat error] ' . $e->getMessage(), compact('userData', 'logContext'));
            $data = [
                'answer' => 'Error - Please verify you have developer access with Open AI. You need to add billing details and add credits for your Open AI developer account. Click here (https://platform.openai.com/account/billing/overview)',
                'failed' => true,
            ];
        }

        $this->chatLog->update([
            ...$data,
            'processed_at' => now(),
        ]);
    }
}
