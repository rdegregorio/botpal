<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\ChatLogApiRequest;
use App\Http\Resources\ChatLogPublicResource;
use App\Http\Resources\ChatLogPublicWithQuestionResource;
use App\Jobs\ChatLogSetCountryCodeJob;
use App\Jobs\ProcessChatRequestJob;
use App\Models\ChatConfig;
use App\Models\ChatLog;
use App\Services\Subscriptions\SubscriptionService;
use Illuminate\Http\Request;

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

        $chatUuid = $request->input('chatUuid');

        $chatConfig = ChatConfig::whereUuid($request->input('chatConfig'))
            ->firstOrFail();

        $ip = $request->ip();

        /** @var ChatLog $message */
        $message = $chatConfig->messages()->create([
            'question' => $request->input('message'),
            'chat_uuid' => $chatUuid,
            'ip_address' => $ip,
        ]);

        dispatch(new ProcessChatRequestJob($message));
        dispatch(new ChatLogSetCountryCodeJob($message->id, $ip));

        SubscriptionService::incrementRequestsCounter($s);

        return response()->json([
            'message' => new ChatLogPublicResource($message),
        ]);
    }

    private function getChatLog(string $chatUuid): ChatLog
    {
        return ChatLog::whereChatUuid($chatUuid)
            ->where('created_at', '>', now()->subDay())
            ->orderBy('id', 'desc')->firstOrFail();
    }
}
