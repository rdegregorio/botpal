@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $colorPrimary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_PRIMARY);
    $colorSecondary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_SECONDARY);
    $chatPlacement = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHAT_PLACEMENT);
    $characterSize = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE);
    $botName = $chatConfig->name ?? 'AI Assistant';
@endphp
  (function() {

    // Modern 2025 CSS styles
    const styles = `
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600&display=swap');

        #chat-wrapper * {
          font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
          box-sizing: border-box;
          margin: 0;
          padding: 0;
          -webkit-font-smoothing: antialiased;
        }

        #chat-wrapper *::-webkit-scrollbar {
          width: 5px;
        }
        #chat-wrapper *::-webkit-scrollbar-track {
          background: transparent;
        }
        #chat-wrapper *::-webkit-scrollbar-thumb {
          background: rgba(0,0,0,0.1);
          border-radius: 10px;
        }
        #chat-wrapper *::-webkit-scrollbar-thumb:hover {
          background: rgba(0,0,0,0.2);
        }

        .chat-block-id {
            display: none;
        }

        .chat-block-id .chat-widget {
            position: relative;
            bottom: auto;
            right: auto;
            width: 100%;
            height: 550px;
            max-height: none;
            border-radius: 24px;
        }

        .chat-overlay {
            display: none;
            position: fixed;
            bottom: 100px;
            @if($chatPlacement == 'left')
            left: 24px;
            @else
            right: 24px;
            @endif
            z-index: 2147483647;
            animation: slideUp 0.4s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(16px) scale(0.98);
            }
            to {
                opacity: 1;
                transform: translateY(0) scale(1);
            }
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .chat-widget {
            background: #ffffff;
            border-radius: 24px;
            width: 400px;
            max-width: calc(100vw - 48px);
            height: 640px;
            max-height: calc(100vh - 140px);
            display: flex;
            flex-direction: column;
            box-shadow:
                0 0 0 1px rgba(0,0,0,0.04),
                0 8px 16px rgba(0,0,0,0.08),
                0 24px 48px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        /* Header */
        .chat-header {
            padding: 20px 24px;
            background: linear-gradient(135deg, {{$colorPrimary}} 0%, {{$colorPrimary}}ee 100%);
            display: flex;
            align-items: center;
            gap: 16px;
            flex-shrink: 0;
        }

        .chat-header-avatar {
            position: relative;
        }

        .chat-header-avatar img {
            width: 48px;
            height: 48px;
            border-radius: 16px;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.2);
        }

        .chat-header-status {
            position: absolute;
            bottom: -2px;
            right: -2px;
            width: 14px;
            height: 14px;
            background: #22c55e;
            border-radius: 50%;
            border: 3px solid {{$colorPrimary}};
        }

        .chat-header-info {
            flex: 1;
            color: white;
        }

        .chat-header-info h2 {
            font-size: 17px;
            font-weight: 600;
            margin-bottom: 3px;
            letter-spacing: -0.2px;
        }

        .chat-header-info p {
            font-size: 13px;
            opacity: 0.85;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .chat-header-info p::before {
            content: '';
            width: 6px;
            height: 6px;
            background: #22c55e;
            border-radius: 50%;
        }

        .chat-header-actions {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .chat-header-btn {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: rgba(255,255,255,0.15);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }

        .chat-header-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: scale(1.05);
        }

        .chat-header-btn svg {
            width: 18px;
            height: 18px;
            stroke: white;
            stroke-width: 2;
            fill: none;
        }

        .chat-close-btn {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            background: rgba(255,255,255,0.15);
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            backdrop-filter: blur(8px);
        }

        .chat-close-btn:hover {
            background: rgba(255,255,255,0.25);
            transform: scale(1.05);
        }

        .chat-close-btn svg {
            width: 18px;
            height: 18px;
            stroke: white;
            stroke-width: 2;
            fill: none;
        }

        /* Expanded widget state */
        .chat-widget.expanded {
            width: 500px;
            height: 80vh;
            max-height: 800px;
        }

        /* Chat Body */
        .chat-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            background: #fafafa;
            overflow: hidden;
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            scroll-behavior: smooth;
        }

        /* Messages */
        .msg-group {
            display: flex;
            gap: 12px;
            animation: fadeIn 0.3s ease;
        }

        .msg-group.user {
            flex-direction: row-reverse;
        }

        .msg-avatar {
            width: 36px;
            height: 36px;
            border-radius: 12px;
            object-fit: cover;
            flex-shrink: 0;
        }

        .msg-group.user .msg-avatar {
            display: none;
        }

        .msg-content {
            max-width: 280px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .msg-bubble {
            padding: 14px 18px;
            font-size: 14.5px;
            line-height: 1.55;
            letter-spacing: -0.1px;
            word-wrap: break-word;
        }

        .msg-bubble.bot {
            background: white;
            color: #1a1a1a;
            border-radius: 20px 20px 20px 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
        }

        .msg-bubble.user {
            background: {{$colorPrimary}};
            color: white;
            border-radius: 20px 20px 6px 20px;
        }

        .msg-time {
            font-size: 11px;
            color: #9ca3af;
            padding: 0 4px;
        }

        .msg-group.user .msg-time {
            text-align: right;
        }

        /* Typing Indicator */
        .typing-indicator {
            display: flex;
            gap: 12px;
            align-items: flex-start;
            animation: fadeIn 0.3s ease;
        }

        .typing-bubble {
            background: white;
            padding: 16px 20px;
            border-radius: 20px 20px 20px 6px;
            box-shadow: 0 1px 3px rgba(0,0,0,0.06);
            display: flex;
            gap: 5px;
            align-items: center;
        }

        .typing-dot {
            width: 8px;
            height: 8px;
            background: #d1d5db;
            border-radius: 50%;
            animation: typingPulse 1.5s ease-in-out infinite;
        }

        .typing-dot:nth-child(2) { animation-delay: 0.15s; }
        .typing-dot:nth-child(3) { animation-delay: 0.3s; }

        @keyframes typingPulse {
            0%, 60%, 100% {
                transform: scale(1);
                background: #d1d5db;
            }
            30% {
                transform: scale(1.15);
                background: #9ca3af;
            }
        }

        /* Input Area */
        .chat-input-area {
            padding: 20px 24px;
            background: white;
            border-top: 1px solid #f0f0f0;
            flex-shrink: 0;
        }

        .chat-input-container {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            background: #f5f5f5;
            border-radius: 20px;
            padding: 6px 6px 6px 20px;
            transition: all 0.2s ease;
            border: 2px solid transparent;
        }

        .chat-input-container:focus-within {
            background: white;
            border-color: {{$colorPrimary}}30;
            box-shadow: 0 0 0 4px {{$colorPrimary}}10;
        }

        .chat-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 15px;
            line-height: 1.5;
            resize: none;
            max-height: 100px;
            min-height: 28px;
            outline: none;
            color: #1a1a1a;
        }

        .chat-input::placeholder {
            color: #9ca3af;
        }

        .chat-send-btn {
            width: 44px;
            height: 44px;
            border-radius: 16px;
            background: {{$colorPrimary}};
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .chat-send-btn:hover:not(:disabled) {
            transform: scale(1.05);
            box-shadow: 0 4px 12px {{$colorPrimary}}40;
        }

        .chat-send-btn:active:not(:disabled) {
            transform: scale(0.98);
        }

        .chat-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .chat-send-btn svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        /* Footer */
        .chat-footer {
            padding: 14px 24px;
            text-align: center;
            font-size: 12px;
            color: #9ca3af;
            background: white;
        }

        .chat-footer a {
            color: {{$colorPrimary}};
            text-decoration: none;
            font-weight: 500;
            transition: opacity 0.2s;
        }

        .chat-footer a:hover {
            opacity: 0.8;
        }

        /* Launcher */
        .chat-launcher {
            position: fixed;
            bottom: 24px;
            @if($chatPlacement == 'left')
            left: 24px;
            @else
            right: 24px;
            @endif
            z-index: 2147483646;
        }

        .chat-launcher-btn {
            width: 64px;
            height: 64px;
            border-radius: 20px;
            background: {{$colorPrimary}};
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow:
                0 4px 12px {{$colorPrimary}}40,
                0 8px 24px rgba(0,0,0,0.12);
            transition: all 0.3s cubic-bezier(0.16, 1, 0.3, 1);
            overflow: hidden;
        }

        .chat-launcher-btn:hover {
            transform: scale(1.05) translateY(-2px);
            box-shadow:
                0 6px 16px {{$colorPrimary}}50,
                0 12px 32px rgba(0,0,0,0.15);
        }

        .chat-launcher-btn img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 20px;
            transition: all 0.3s ease;
        }

        .chat-launcher-btn svg {
            width: 28px;
            height: 28px;
            fill: white;
            position: absolute;
            transition: all 0.3s ease;
        }

        .chat-launcher-btn .icon-open {
            opacity: 0;
            transform: rotate(-90deg) scale(0.5);
        }

        .chat-launcher-btn .icon-close {
            opacity: 0;
            transform: rotate(90deg) scale(0.5);
        }

        .chat-launcher.closed .chat-launcher-btn img {
            opacity: 1;
            transform: scale(1);
        }

        .chat-launcher.closed .chat-launcher-btn .icon-open {
            opacity: 0;
        }

        .chat-launcher.opened .chat-launcher-btn img {
            opacity: 0;
            transform: scale(0.5);
        }

        .chat-launcher.opened .chat-launcher-btn .icon-close {
            opacity: 1;
            transform: rotate(0) scale(1);
        }

        /* Notification badge */
        .chat-launcher-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            width: 20px;
            height: 20px;
            background: #ef4444;
            border-radius: 50%;
            display: none;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 600;
            color: white;
            border: 3px solid white;
        }

        /* Quick replies (future) */
        .quick-replies {
            display: flex;
            gap: 8px;
            flex-wrap: wrap;
            margin-top: 8px;
        }

        .quick-reply {
            padding: 8px 16px;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 20px;
            font-size: 13px;
            color: #374151;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .quick-reply:hover {
            border-color: {{$colorPrimary}};
            color: {{$colorPrimary}};
        }
    `;

    // HTML structure
    const htmlTemplate = \`
        <div id="chat-wrapper">
            <div @if(request('blockId')) class="chat-block-id" @else class="chat-overlay" @endif id="chatModal">
                <div class="chat-widget">
                    <div class="chat-header">
                        <div class="chat-header-avatar">
                            <img src="{{$chatConfig?->character_url}}" alt="{{$botName}}">
                            <div class="chat-header-status"></div>
                        </div>
                        <div class="chat-header-info">
                            <h2>{{$botName}}</h2>
                            <p>Online now</p>
                        </div>
                        <div class="chat-header-actions">
                            <button class="chat-header-btn" id="chatRefreshBtn" title="New conversation">
                                <svg viewBox="0 0 24 24"><path d="M3 12a9 9 0 0 1 9-9 9.75 9.75 0 0 1 6.74 2.74L21 8" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 3v5h-5" stroke-linecap="round" stroke-linejoin="round"/><path d="M21 12a9 9 0 0 1-9 9 9.75 9.75 0 0 1-6.74-2.74L3 16" stroke-linecap="round" stroke-linejoin="round"/><path d="M8 16H3v5" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <button class="chat-header-btn" id="chatExpandBtn" title="Expand chat">
                                <svg viewBox="0 0 24 24" id="expandIcon"><path d="M15 3h6v6M9 21H3v-6M21 3l-7 7M3 21l7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                            <button class="chat-close-btn" id="chatCloseBtn">
                                <svg viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" stroke-linecap="round" stroke-linejoin="round"/></svg>
                            </button>
                        </div>
                    </div>
                    <div class="chat-body">
                        <div class="chat-messages" id="chatMessages">
                            <div class="msg-group bot">
                                <img class="msg-avatar" src="{{$chatConfig?->character_url}}" alt="{{$botName}}">
                                <div class="msg-content">
                                    <div class="msg-bubble bot">{{$chatConfig?->welcome_message ?? 'Hi there! How can I help you today?'}}</div>
                                    <div class="msg-time">Just now</div>
                                </div>
                            </div>
                            <div id="typingIndicator" class="typing-indicator" style="display: none;">
                                <img class="msg-avatar" src="{{$chatConfig?->character_url}}" alt="{{$botName}}">
                                <div class="typing-bubble">
                                    <div class="typing-dot"></div>
                                    <div class="typing-dot"></div>
                                    <div class="typing-dot"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-input-area">
                        <div class="chat-input-container">
                            <textarea class="chat-input" id="userMessage" placeholder="Type your message..." rows="1"></textarea>
                            <button class="chat-send-btn" id="sendBtn" data-config="{{$chatConfig?->uuid}}">
                                <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                            </button>
                        </div>
                    </div>
                    @if($chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COPYRIGHT_ENABLED))
                    <div class="chat-footer">
                        Powered by <a href="{{url('/')}}" target="_blank">aisupport.bot</a>
                    </div>
                    @endif
                </div>
            </div>
            <div id="chatLauncher" class="chat-launcher closed">
                <button class="chat-launcher-btn">
                    <img src="{{$chatConfig?->character_url}}" alt="Chat">
                    <svg class="icon-close" viewBox="0 0 24 24"><path d="M18 6L6 18M6 6l12 12" stroke="white" stroke-width="2.5" stroke-linecap="round" fill="none"/></svg>
                </button>
            </div>
        </div>
    \`;

    function getTimeString() {
        return new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });
    }

    function insertMessage(content, isUser) {
        const avatarUrl = '{{$chatConfig?->character_url}}';
        const botName = '{{$botName}}';
        const time = getTimeString();
        const messagesContainer = document.getElementById('chatMessages');

        const html = isUser ? \`
            <div class="msg-group user">
                <div class="msg-content">
                    <div class="msg-bubble user">\${content}</div>
                    <div class="msg-time">\${time}</div>
                </div>
            </div>
        \` : \`
            <div class="msg-group bot">
                <img class="msg-avatar" src="\${avatarUrl}" alt="\${botName}">
                <div class="msg-content">
                    <div class="msg-bubble bot">\${content}</div>
                    <div class="msg-time">\${time}</div>
                </div>
            </div>
        \`;

        messagesContainer.insertAdjacentHTML('beforeend', html);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;
    }

    function showTyping(show) {
        const indicator = document.getElementById('typingIndicator');
        indicator.style.display = show ? 'flex' : 'none';
        if (show) {
            document.getElementById('chatMessages').scrollTop = document.getElementById('chatMessages').scrollHeight;
        }
    }

    // Inject styles
    const styleEl = document.createElement('style');
    styleEl.textContent = styles;
    document.head.appendChild(styleEl);

    // Inject HTML
    const container = document.createElement('div');
    container.innerHTML = htmlTemplate;
    document.body.appendChild(container);

    // Elements
    const modal = document.getElementById('chatModal');
    const launcher = document.getElementById('chatLauncher');
    const closeBtn = document.getElementById('chatCloseBtn');
    const refreshBtn = document.getElementById('chatRefreshBtn');
    const expandBtn = document.getElementById('chatExpandBtn');
    const chatWidget = document.querySelector('.chat-widget');
    const sendBtn = document.getElementById('sendBtn');
    const input = document.getElementById('userMessage');

    // Auto-resize textarea
    input.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = Math.min(this.scrollHeight, 100) + 'px';
    });

    // Chat state
    const storageKey = 'chat_{{$chatConfig->uuid}}';
    const openedKey = 'chatOpen_{{$chatConfig->uuid}}';
    let lastMsg = localStorage.getItem(storageKey) ? JSON.parse(localStorage.getItem(storageKey)) : null;
    let isOpen = false;

    function toggleChat(save = true) {
        isOpen = !isOpen;
        modal.style.display = isOpen ? 'block' : 'none';
        launcher.className = 'chat-launcher ' + (isOpen ? 'opened' : 'closed');
        if (save) localStorage.setItem(openedKey, isOpen);
        if (isOpen) input.focus();
    }

    launcher.addEventListener('click', () => toggleChat());
    closeBtn.addEventListener('click', () => toggleChat());

    // Refresh/Clear chat - start new conversation
    refreshBtn.addEventListener('click', function() {
        const messagesContainer = document.getElementById('chatMessages');
        const avatarUrl = '{{$chatConfig?->character_url}}';
        const botName = '{{$botName}}';
        const welcomeMessage = '{{$chatConfig?->welcome_message ?? "Hi there! How can I help you today?"}}';

        // Clear stored chat data
        localStorage.removeItem(storageKey);
        lastMsg = null;

        // Reset messages to just the welcome message
        messagesContainer.innerHTML = \`
            <div class="msg-group bot">
                <img class="msg-avatar" src="\${avatarUrl}" alt="\${botName}">
                <div class="msg-content">
                    <div class="msg-bubble bot">\${welcomeMessage}</div>
                    <div class="msg-time">Just now</div>
                </div>
            </div>
            <div id="typingIndicator" class="typing-indicator" style="display: none;">
                <img class="msg-avatar" src="\${avatarUrl}" alt="\${botName}">
                <div class="typing-bubble">
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                    <div class="typing-dot"></div>
                </div>
            </div>
        \`;
    });

    // Expand/Collapse chat widget
    const expandedKey = 'chatExpanded_{{$chatConfig->uuid}}';
    let isExpanded = localStorage.getItem(expandedKey) === 'true';

    function setExpanded(expanded) {
        isExpanded = expanded;
        if (expanded) {
            chatWidget.classList.add('expanded');
            expandBtn.title = 'Collapse chat';
        } else {
            chatWidget.classList.remove('expanded');
            expandBtn.title = 'Expand chat';
        }
        localStorage.setItem(expandedKey, expanded);
    }

    // Restore expanded state
    if (isExpanded) {
        setExpanded(true);
    }

    expandBtn.addEventListener('click', function() {
        setExpanded(!isExpanded);
    });

    // Send message
    input.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendBtn.click();
        }
    });

    sendBtn.addEventListener('click', async function() {
        const msg = input.value.trim();
        if (!msg) return;

        const config = this.dataset.config;
        sendBtn.disabled = true;
        input.value = '';
        input.style.height = 'auto';

        insertMessage(msg, true);
        showTyping(true);

        const body = { message: msg, chatConfig: config };
        if (lastMsg?.chat_uuid) body.chatUuid = lastMsg.chat_uuid;

        try {
            const res = await fetch('{{route("api.chat.message")}}', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json' },
                body: JSON.stringify(body)
            });

            const data = await res.json();
            showTyping(false);

            if (!res.ok || data.error) {
                insertMessage(data.message || 'Sorry, something went wrong.', false);
            } else if (data.message?.answer) {
                lastMsg = data.message;
                localStorage.setItem(storageKey, JSON.stringify(lastMsg));
                insertMessage(data.message.answer, false);
            }
        } catch (err) {
            showTyping(false);
            insertMessage('Connection error. Please try again.', false);
        }

        sendBtn.disabled = false;
    });

    // Restore state
    if (lastMsg && localStorage.getItem(openedKey) === 'true') {
        toggleChat(false);
    }

    @if(request('blockId'))
    const blockEl = document.getElementById('{{ request("blockId") }}');
    const chatEmbed = document.querySelector('.chat-block-id');
    if (blockEl && chatEmbed) {
        blockEl.appendChild(chatEmbed);
        chatEmbed.style.display = 'block';
        launcher.style.display = 'none';
    }
    @endif

  })();
