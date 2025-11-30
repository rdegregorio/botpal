@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $chatConfig = Auth::user()->chatConfigLatest;
@endphp

@extends('layouts.dashboard')

@section('page-title', 'Preview ChatBot')

@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title">Live Preview</h2>
        </div>
        <p class="mb-4" style="color: var(--text-secondary);">Test your chatbot below to see how it will appear and respond to your visitors.</p>

        <div id="chat-box" style="min-height: 500px; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;"></div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-code-slash me-2"></i>Embed Code</h2>
        </div>
        <p class="mb-3" style="color: var(--text-secondary);">Copy and paste this code into your website to add the chatbot.</p>
        <div class="p-3 rounded" style="background: #1a1a1a; font-family: monospace; font-size: 13px; color: #22c55e; overflow-x: auto;">
            <code>&lt;script async src="{{ route('api.chat.embed', $chatConfig?->uuid) }}"&gt;&lt;/script&gt;</code>
        </div>
        <button class="btn btn-outline-primary btn-sm mt-3" onclick="copyEmbedCode()">
            <i class="bi bi-clipboard me-1"></i> Copy Code
        </button>
    </div>
@endsection

@push('bottom')
    <script async src="{{route('api.chat.embed', $chatConfig?->uuid)}}?blockId=chat-box"></script>
    <style>
        #chat-box {
            background: #fafafa;
        }
        #chat-wrapper #avatar, .chat-dialog .chat-close {
            display: none !important;
        }
    </style>
    <script>
        function copyEmbedCode() {
            var code = '<script async src="{{ route('api.chat.embed', $chatConfig?->uuid) }}"><\/script>';
            navigator.clipboard.writeText(code).then(function() {
                alert('Embed code copied to clipboard!');
            });
        }
    </script>
@endpush
