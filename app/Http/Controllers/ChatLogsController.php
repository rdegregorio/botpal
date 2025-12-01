<?php

namespace App\Http\Controllers;

use App\Http\Resources\ChatLogDashboardResource;
use App\Models\ChatLog;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ChatLogsController extends Controller
{
    public function index()
    {
        $chatConfig = \Auth::user()->chatConfigLatest;

        if (!$chatConfig) {
            return redirect()->route('dashboard')->with('warning', 'Please set up your chatbot first.');
        }

        $totalMessages = $chatConfig->messages()->regular()->count();

        return view('messages.index', compact('totalMessages'));
    }

    public function actionMessage(Request $request)
    {
        $data = $request->validate([
            'message_uuid' => 'required|uuid',
            'action' => [
                'required',
                'string',
                Rule::in([
                    'archive',
                    'delete',
                    'flag',
                    'unarchive',
                    'unflag',
                    'restore',
                    'permanently_delete',
                ])
            ],
        ]);

        $chatConfig = \Auth::user()->chatConfigLatest;

        if (!$chatConfig) {
            return response()->json(['error' => 'No chatbot configured'], 404);
        }

        $message = ChatLog::whereMessageUuid($data['message_uuid'])->whereChatConfigId($chatConfig->id)->firstOrFail();

        if ($data['action'] === 'permanently_delete') {
            $message->delete();
        } else {
            match ($data['action']) {
                'archive' => $message->is_archived = true,
                'delete' => $message->is_deleted = true,
                'flag' => $message->is_flagged = true,
                'unarchive' => $message->is_archived = false,
                'unflag' => $message->is_flagged = false,
                'restore' => $message->is_deleted = false,
            };
            $message->save();
        }

        return response()->json([
            'success' => true
        ]);
    }

    public function getMessages(Request $request)
    {
        $data = $request->validate([
            'type' => 'required|string|in:inbox,archived,flagged,deleted',
        ]);

        $chatConfig = \Auth::user()->chatConfigLatest;

        if (!$chatConfig) {
            return response()->json(['data' => []]);
        }

        $type = $data['type'];

        $perPage = 100;

        $messages = $chatConfig
            ->messages()
            ->when($type === 'inbox', function ($query) {
                $query->regular();
            })
            ->when($type === 'archived', function ($query) {
                $query->where([
                    'is_archived' => true,
                ]);
            })
            ->when($type === 'flagged', function ($query) {
                $query->where([
                    'is_flagged' => true,
                ]);
            })
            ->when($type === 'deleted', function ($query) {
                $query->where([
                    'is_deleted' => true,
                ]);
            })
            ->when(\Auth::user()->getCurrentActiveSubscription()?->isFree(), function () use (&$perPage) {
                $perPage = 1;
            })->latest()->simplePaginate($perPage);

        return ChatLogDashboardResource::collection($messages);
    }
}
