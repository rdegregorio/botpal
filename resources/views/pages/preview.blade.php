@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $chatConfig = Auth::user()->chatConfigLatest;
    $hasApiKey = !empty(Auth::user()->open_ai_token);
    $hasModel = !empty(Auth::user()->open_ai_model);
    $isReady = $chatConfig?->uuid && $hasApiKey && $hasModel;
@endphp

@extends('layouts.dashboard')

@section('page-title', 'Preview ChatBot')

@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-eye me-2"></i>Live Preview</h2>
        </div>
        <p class="mb-4" style="color: var(--text-secondary);">Test your chatbot below to see how it will appear and respond to your visitors.</p>

        @if($isReady)
            <div id="chat-box" style="min-height: 500px; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;"></div>
        @else
            <div class="text-center py-5" style="background: var(--bg-cream); border-radius: 12px;">
                <i class="bi bi-robot" style="font-size: 48px; color: var(--text-secondary);"></i>
                <h5 class="mt-3 mb-3">Complete Setup to Preview</h5>
                <div class="text-start mx-auto" style="max-width: 300px;">
                    <div class="d-flex align-items-center mb-2">
                        @if($chatConfig?->uuid)
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                        @else
                            <i class="bi bi-circle text-muted me-2"></i>
                        @endif
                        <span style="color: var(--text-secondary);">ChatBot configured</span>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        @if($hasApiKey)
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                        @else
                            <i class="bi bi-circle text-muted me-2"></i>
                        @endif
                        <span style="color: var(--text-secondary);">OpenAI API key added</span>
                    </div>
                    <div class="d-flex align-items-center mb-3">
                        @if($hasModel)
                            <i class="bi bi-check-circle-fill text-success me-2"></i>
                        @else
                            <i class="bi bi-circle text-muted me-2"></i>
                        @endif
                        <span style="color: var(--text-secondary);">OpenAI model selected</span>
                    </div>
                </div>
                <a href="{{ route('dashboard') }}" class="btn btn-primary">
                    <i class="bi bi-gear me-1"></i> Go to ChatBot Setup
                </a>
            </div>
        @endif
    </div>
@endsection

@push('bottom')
    @if($isReady)
    <script async src="{{route('api.chat.embed', $chatConfig->uuid)}}?blockId=chat-box"></script>
    <style>
        #chat-box {
            background: #fafafa;
        }
        #chat-wrapper #avatar, .chat-dialog .chat-close {
            display: none !important;
        }
    </style>
    @endif
@endpush
