<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChatLogApiRequest;
use App\Http\Resources\ChatLogPublicResource;
use App\Http\Resources\ChatLogPublicWithQuestionResource;
use App\Jobs\ChatLogSetCountryCodeJob;
use App\Models\ChatConfig;
use App\Models\ChatLog;
use App\Services\OpenAIService;
use App\Services\Subscriptions\SubscriptionService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ChatBotController extends Controller
{
    public function embed(string $uuid)
    {
        $chatConfig = ChatConfig::whereUuid($uuid)->active()->first();

        if (!$chatConfig) {
            $content = view('api.chat-error', ['message' => 'Chat config not found'])->render();
        } elseif (!config('services.openai.api_key')) {
            $content = view('api.chat-error', ['message' => 'OpenAI API key not configured'])->render();
        } elseif ($chatConfig->hasLimits()) {
            $user = $chatConfig->user;
            $isFreeUser = $user->getCurrentActiveSubscription()?->isFree();
            $content = view('api.chat', compact('chatConfig', 'isFreeUser'))->render();
        } else {
            $content = view('api.chat-error', ['message' => 'Chat quota limit exceeded'])->render();
        }

        return response($content)->withHeaders([
            'Content-Type' => 'application/javascript',
        ]);
    }

    public function result(Request $request)
    {
        $request->validate([
            'messageUuid' => 'required|uuid',
            'with-history' => 'nullable|boolean',
        ]);

        $message = ChatLog::whereMessageUuid($request->input('messageUuid'))
            ->where('created_at', '>', now()->subMinutes(30))
            ->firstOrFail();

        $history = null;

        if ($request->input('with-history')) {
            $messages = ChatLog::whereChatUuid($message->chat_uuid)
                ->orderBy('id')->get();
            $history = ChatLogPublicWithQuestionResource::collection($messages);
        }

        return response()->json([
            'message' => new ChatLogPublicResource($message),
            'history' => $history,
        ]);
    }

    public function request(ChatLogApiRequest $request)
    {
        $chatConfig = ChatConfig::whereUuid($request->input('chatConfig'))->firstOrFail();

        if (!$chatConfig->hasLimits()) {
            return response()->json([
                'error' => true,
                'message' => 'Chat quota limit exceeded',
            ]);
        }

        $s = $chatConfig->user->getCurrentActiveSubscription();

        if (!$s) {
            return response()->json([
                'error' => true,
                'message' => 'Subscription not found',
            ]);
        }

        // Generate new chat_uuid for first message, or use existing one for conversation continuity
        $chatUuid = $request->input('chatUuid') ?: (string) Str::uuid();
        // Always generate a new message_uuid for each message
        $messageUuid = (string) Str::uuid();
        $ip = $request->ip();

        /** @var ChatLog $message */
        $message = $chatConfig->messages()->create([
            'question' => $request->input('message'),
            'chat_uuid' => $chatUuid,
            'message_uuid' => $messageUuid,
            'ip_address' => $ip,
        ]);

        // Process OpenAI request synchronously for instant response
        $answer = $this->processOpenAIRequest($message, $chatConfig);

        $message->update([
            'answer' => $answer['text'],
            'prompt_tokens' => $answer['prompt_tokens'] ?? 0,
            'completion_tokens' => $answer['completion_tokens'] ?? 0,
            'failed' => $answer['failed'] ?? false,
            'processed_at' => now(),
        ]);

        // Background job for country code (non-blocking)
        dispatch(new ChatLogSetCountryCodeJob($message->id, $ip));

        SubscriptionService::incrementRequestsCounter($s);

        // Refresh to get updated answer
        $message->refresh();

        return response()->json([
            'message' => new ChatLogPublicResource($message),
        ]);
    }

    private function processOpenAIRequest(ChatLog $chatLog, ChatConfig $chatConfig): array
    {
        $apiKey = config('services.openai.api_key');

        if (!$apiKey) {
            return [
                'text' => 'OpenAI API key not configured',
                'failed' => true,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
            ];
        }

        $model = $chatConfig->getSettings(ChatConfig::SETTINGS_AI_MODEL)
            ?? config('services.openai.default_model', 'gpt-5-mini');

        $service = new OpenAIService($apiKey, $model);

        $systemPrompt = $chatConfig->general_prompt;
        $systemPrompt .= "\n\n{$chatConfig->welcome_message}\n\nEnsure to avoid answering questions that are unrelated to the service.";

        $context = $chatConfig->getContext();
        if ($context) {
            $systemPrompt .= "\n\nContext: " . $context;
        }

        // Get conversation history
        $history = ChatLog::whereChatUuid($chatLog->chat_uuid)
            ->where('id', '<>', $chatLog->id)
            ->orderBy('id')
            ->get();

        $config = $service->getConfig(OpenAIService::CHAT_LOG);
        $config['system_prompt'] = $systemPrompt;
        $config['prompt'] = $chatLog->question;

        if ($history->isNotEmpty()) {
            foreach ($history as $log) {
                $config['conversation'][] = [
                    'role' => 'user',
                    'content' => $log->question,
                ];
                if ($log->answer) {
                    $config['conversation'][] = [
                        'role' => 'assistant',
                        'content' => $log->answer,
                    ];
                }
            }
        }

        try {
            $response = $service->requestGptTurbo($config);
            $text = trim($response['choices'][0]['message']['content'], " \t\n\r\0\x0B\"");

            return [
                'text' => $text,
                'prompt_tokens' => $response['usage']['prompt_tokens'],
                'completion_tokens' => $response['usage']['completion_tokens'],
                'failed' => false,
            ];
        } catch (\Exception $e) {
            \Log::channel('openai')->error('[Chat error] ' . $e->getMessage());

            return [
                'text' => 'Sorry, I encountered an error processing your request. Please try again.',
                'failed' => true,
                'prompt_tokens' => 0,
                'completion_tokens' => 0,
            ];
        }
    }

    private function getChatLog(string $chatUuid): ChatLog
    {
        return ChatLog::whereChatUuid($chatUuid)
            ->where('created_at', '>', now()->subDay())
            ->orderBy('id', 'desc')->firstOrFail();
    }
}
