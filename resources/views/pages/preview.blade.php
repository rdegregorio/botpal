@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $chatConfig = Auth::user()->chatConfigLatest;
    $isReady = $chatConfig?->uuid;
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
            <div id="chat-box" style="height: 450px; border: 1px solid var(--border); border-radius: 12px; overflow: hidden;"></div>
        @else
            <div class="text-center py-5" style="background: var(--bg-cream); border-radius: 12px;">
                <i class="bi bi-robot" style="font-size: 48px; color: var(--text-secondary);"></i>
                <h5 class="mt-3 mb-3">Complete Setup to Preview</h5>
                <p style="color: var(--text-secondary);">Configure your chatbot in the Appearance section first.</p>
                <a href="{{ route('settings') }}" class="btn btn-primary">
                    <i class="bi bi-palette me-1"></i> Go to Appearance
                </a>
            </div>
        @endif
    </div>

    @if($isReady)
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-code-slash me-2"></i>Chat Widget Code</h2>
        </div>
        <p class="mb-3" style="color: var(--text-secondary); font-size: 14px;">Copy and paste this code into your website to add the chatbot.</p>
        <div class="code-container position-relative">
            <pre id="chatCodeBox" class="code-box p-3 rounded" style="background: #f5f5f5; border: 1px solid #ddd; font-size: 13px; overflow-x: auto;">&lt;!-- Start of aisupport.bot Embed Code--&gt;
&lt;script async src="{{ route('api.chat.embed', $chatConfig->uuid) }}"&gt;&lt;/script&gt;
&lt;!-- End of aisupport.bot Embed Code --&gt;</pre>
            <button class="btn btn-sm btn-outline-secondary copy-btn" style="position: absolute; top: 10px; right: 10px;" onclick="copyEmbedCode()" title="Copy Code">
                <i class="bi bi-clipboard"></i> Copy
            </button>
        </div>
    </div>
    @endif
@endsection

@push('bottom')
    @if($isReady)
    <script async src="{{ route('api.chat.embed', $chatConfig->uuid) }}?blockId=chat-box"></script>
    <script>
        function copyEmbedCode() {
            var codeBox = document.getElementById('chatCodeBox');
            var textToCopy = codeBox.textContent || codeBox.innerText;

            navigator.clipboard.writeText(textToCopy.trim()).then(function() {
                var btn = document.querySelector('.copy-btn');
                var originalHTML = btn.innerHTML;
                btn.innerHTML = '<i class="bi bi-check"></i> Copied!';
                setTimeout(function() {
                    btn.innerHTML = originalHTML;
                }, 2000);
            }).catch(function() {
                var range = document.createRange();
                range.selectNode(codeBox);
                window.getSelection().removeAllRanges();
                window.getSelection().addRange(range);
                document.execCommand('copy');
                window.getSelection().removeAllRanges();
                alert('Code copied to clipboard!');
            });
        }
    </script>
    <style>
        #chat-box {
            background: #fafafa;
        }
        /* Hide the floating avatar button - not needed for test page */
        #chat-wrapper .chat-avatar,
        #chatAvatar {
            display: none !important;
        }
        /* Hide close button */
        .chat-dialog .chat-close {
            display: none !important;
        }
        /* Make chat dialog contained with internal scroll */
        #chat-wrapper .chat-dialog {
            max-height: 420px !important;
            height: 420px !important;
            overflow: hidden;
        }
        #chat-wrapper .chat-content {
            max-height: 300px !important;
            overflow-y: auto !important;
        }
        /* Ensure block mode displays properly */
        #chat-wrapper .chat-block-id {
            display: block !important;
        }
    </style>
    @endif
@endpush
