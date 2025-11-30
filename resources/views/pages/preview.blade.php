@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $chatConfig = Auth::user()->chatConfigLatest;
@endphp

@extends('layouts.dashboard')

@section('page-title', 'Preview ChatBot')

@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-eye me-2"></i>Live Preview</h2>
        </div>
        <p class="mb-4" style="color: var(--text-secondary);">Test your chatbot below to see how it will appear and respond to your visitors.</p>

        @if($chatConfig?->uuid)
            <div id="chat-box" style="min-height: 500px; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;"></div>
        @else
            <div class="text-center py-5" style="background: var(--bg-cream); border-radius: 12px;">
                <i class="bi bi-robot" style="font-size: 48px; color: var(--text-secondary);"></i>
                <p class="mt-3 mb-0" style="color: var(--text-secondary);">Complete your <a href="{{ route('dashboard') }}">ChatBot Setup</a> first to preview your chatbot.</p>
            </div>
        @endif
    </div>
@endsection

@push('bottom')
    @if($chatConfig?->uuid)
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
