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
            <div id="chat-box" style="border: 1px solid var(--border); border-radius: 12px; overflow: hidden;"></div>
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
    <script>
        // Clear and reload chat widget when navigating back to the page
        (function() {
            // Remove any existing chat widget to prevent duplicates
            const existingWrapper = document.getElementById('chat-wrapper');
            if (existingWrapper) {
                existingWrapper.remove();
            }

            // Also remove any chat that was moved into the chat-box
            const chatBox = document.getElementById('chat-box');
            if (chatBox) {
                chatBox.innerHTML = '';
            }

            // Remove any old chat styles
            document.querySelectorAll('style').forEach(style => {
                if (style.innerText && style.innerText.includes('chat-wrapper')) {
                    style.remove();
                }
            });

            // Load the chat widget script
            const script = document.createElement('script');
            script.src = "{{ route('api.chat.embed', $chatConfig->uuid) }}?blockId=chat-box&t=" + Date.now();
            document.body.appendChild(script);
        })();
    </script>
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
            display: flex !important;
            flex-direction: column !important;
            height: 500px !important;
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
        /* Make chat fill the container */
        #chat-box .chat-block-id {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
            height: 100% !important;
        }
        #chat-box .chat-dialog {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
            height: 100% !important;
            max-height: none !important;
            overflow: hidden !important;
            padding: 0 !important;
            width: 100% !important;
        }
        #chat-box .chat-header {
            border-radius: 0 !important;
        }
        #chat-box .chat-body {
            display: flex !important;
            flex-direction: column !important;
            flex: 1 !important;
            overflow: hidden !important;
        }
        #chat-box .chat-content {
            flex: 1 !important;
            overflow-y: auto !important;
            min-height: 0 !important;
        }
        #chat-box .chat-input-group {
            display: flex !important;
            flex-shrink: 0 !important;
        }
        #chat-box .chat-input {
            flex: 1 !important;
            padding: 10px !important;
            border: 1px solid #ccc !important;
            border-radius: 5px !important;
            margin-right: 10px !important;
        }
        #chat-box .chat-send-btn {
            padding: 10px 20px !important;
            border: none !important;
            border-radius: 5px !important;
            cursor: pointer !important;
        }
        /* Hide the copyright in preview - it shows outside container */
        #chat-box .chat-copy {
            display: none !important;
        }
        /* Hide expand button in preview (already full width) */
        #chat-box #expandChatBtn {
            display: none !important;
        }
    </style>
    @endif
@endpush
