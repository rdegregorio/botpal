@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $colorPrimary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_PRIMARY);
    $colorSecondary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_SECONDARY);
    $chatPlacement = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHAT_PLACEMENT);
    $characterSize = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE);
    $botName = $chatConfig->name ?? 'AI Assistant';
@endphp
  (function() {

    // CSS styles for the widget - Intercom-style design
    const styles = `
        #chat-wrapper * {
          font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, sans-serif !important;
          box-sizing: border-box;
          margin: 0;
          padding: 0;
        }
        #chat-wrapper *::-webkit-scrollbar {
          width: 6px;
        }
        #chat-wrapper *::-webkit-scrollbar-track {
          background: transparent;
        }
        #chat-wrapper *::-webkit-scrollbar-thumb {
          background-color: rgba(0, 0, 0, 0.2);
          border-radius: 3px;
        }

        .chat-block-id {
            display: none;
        }

        .chat-block-id .chat-dialog {
            position: relative;
            bottom: auto;
            right: auto;
            width: 100%;
            height: 500px;
            max-height: none;
            border-radius: 16px;
        }

        .chat-overlay {
            display: none;
            position: fixed;
            bottom: 90px;
            @if($chatPlacement == 'left')
            left: 20px;
            @else
            right: 20px;
            @endif
            z-index: 2147483647;
            animation: chatSlideUp 0.3s ease-out;
        }

        @keyframes chatSlideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .chat-dialog {
            background-color: #fff;
            border-radius: 16px;
            width: 380px;
            max-width: calc(100vw - 40px);
            height: 600px;
            max-height: calc(100vh - 120px);
            display: flex;
            flex-direction: column;
            box-shadow: 0 5px 40px rgba(0,0,0,0.16);
            overflow: hidden;
        }

        .chat-header {
            display: flex;
            align-items: center;
            padding: 16px 20px;
            background: linear-gradient(135deg, {{$colorPrimary}} 0%, {{$colorPrimary}}dd 100%);
            color: white;
            flex-shrink: 0;
        }

        .chat-header-back {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 8px;
            margin-right: 12px;
            transition: background 0.2s;
        }

        .chat-header-back:hover {
            background: rgba(255,255,255,0.1);
        }

        .chat-header-back svg {
            width: 20px;
            height: 20px;
            fill: white;
        }

        .chat-header-info {
            flex: 1;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .chat-header-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            object-fit: cover;
            border: 2px solid rgba(255,255,255,0.3);
        }

        .chat-header-text h3 {
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 2px;
        }

        .chat-header-text p {
            font-size: 13px;
            opacity: 0.85;
        }

        .chat-header-actions {
            display: flex;
            gap: 8px;
        }

        .chat-header-btn {
            width: 32px;
            height: 32px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            border-radius: 8px;
            transition: background 0.2s;
        }

        .chat-header-btn:hover {
            background: rgba(255,255,255,0.1);
        }

        .chat-header-btn svg {
            width: 18px;
            height: 18px;
            fill: white;
        }

        .chat-body {
            flex: 1;
            display: flex;
            flex-direction: column;
            overflow: hidden;
            background: #f9f9f9;
        }

        .chat-content {
            flex: 1;
            overflow-y: auto;
            padding: 20px;
        }

        .chat-messages {
            display: flex;
            flex-direction: column;
            gap: 16px;
        }

        .chat-message-group {
            display: flex;
            gap: 10px;
            align-items: flex-start;
        }

        .chat-message-group.user {
            flex-direction: row-reverse;
        }

        .chat-message-avatar {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            object-fit: cover;
            flex-shrink: 0;
        }

        .chat-message-group.user .chat-message-avatar {
            display: none;
        }

        .chat-message-content {
            max-width: 75%;
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .chat-message {
            padding: 12px 16px;
            border-radius: 18px;
            font-size: 14px;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .chat-message.bot {
            background: white;
            color: #1f1f1f;
            border-bottom-left-radius: 6px;
            box-shadow: 0 1px 2px rgba(0,0,0,0.08);
        }

        .chat-message.user {
            background: {{$colorPrimary}};
            color: white;
            border-bottom-right-radius: 6px;
        }

        .chat-message-meta {
            font-size: 11px;
            color: #8c8c8c;
            padding: 0 4px;
        }

        .chat-message-group.user .chat-message-meta {
            text-align: right;
        }

        .typing-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .typing-indicator .chat-message {
            padding: 14px 18px;
        }

        .typing-dots {
            display: flex;
            gap: 4px;
        }

        .typing-dots span {
            width: 8px;
            height: 8px;
            background: #8c8c8c;
            border-radius: 50%;
            animation: typingBounce 1.4s infinite ease-in-out both;
        }

        .typing-dots span:nth-child(1) { animation-delay: -0.32s; }
        .typing-dots span:nth-child(2) { animation-delay: -0.16s; }
        .typing-dots span:nth-child(3) { animation-delay: 0s; }

        @keyframes typingBounce {
            0%, 80%, 100% { transform: scale(0.8); opacity: 0.5; }
            40% { transform: scale(1); opacity: 1; }
        }

        .chat-input-area {
            padding: 16px 20px;
            background: white;
            border-top: 1px solid #e8e8e8;
            flex-shrink: 0;
        }

        .chat-input-wrapper {
            display: flex;
            align-items: flex-end;
            gap: 12px;
            background: #f5f5f5;
            border-radius: 24px;
            padding: 8px 8px 8px 18px;
            transition: box-shadow 0.2s, background 0.2s;
        }

        .chat-input-wrapper:focus-within {
            background: white;
            box-shadow: 0 0 0 2px {{$colorPrimary}}40;
        }

        .chat-input {
            flex: 1;
            border: none;
            background: transparent;
            font-size: 14px;
            line-height: 1.5;
            resize: none;
            max-height: 120px;
            min-height: 24px;
            outline: none;
        }

        .chat-input::placeholder {
            color: #8c8c8c;
        }

        .chat-send-btn {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background: {{$colorPrimary}};
            border: none;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.2s, opacity 0.2s;
            flex-shrink: 0;
        }

        .chat-send-btn:hover {
            transform: scale(1.05);
        }

        .chat-send-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
            transform: none;
        }

        .chat-send-btn svg {
            width: 18px;
            height: 18px;
            fill: white;
        }

        .chat-footer {
            padding: 12px 20px;
            text-align: center;
            font-size: 12px;
            color: #8c8c8c;
            background: white;
            border-top: 1px solid #f0f0f0;
        }

        .chat-footer a {
            color: {{$colorPrimary}};
            text-decoration: none;
            font-weight: 500;
        }

        .chat-footer a:hover {
            text-decoration: underline;
        }

        /* Launcher button */
        .chat-launcher {
            position: fixed;
            bottom: 20px;
            @if($chatPlacement == 'left')
            left: 20px;
            @else
            right: 20px;
            @endif
            z-index: 2147483646;
            cursor: pointer;
            transition: transform 0.2s;
        }

        .chat-launcher:hover {
            transform: scale(1.05);
        }

        .chat-launcher-btn {
            width: 60px;
            height: 60px;
            border-radius: 50%;
            background: {{$colorPrimary}};
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 16px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }

        .chat-launcher-btn img {
            width: 100%;
            height: 100%;
            border-radius: 50%;
            object-fit: cover;
        }

        .chat-launcher-btn svg {
            width: 28px;
            height: 28px;
            fill: white;
        }

        .chat-launcher.opened .chat-launcher-btn {
            transform: rotate(0deg);
        }

        .chat-launcher.opened .launcher-open {
            display: none;
        }

        .chat-launcher.opened .launcher-close {
            display: block;
        }

        .launcher-close {
            display: none;
        }
    `;

    // HTML structure for the widget
    const htmlTemplate = `
        <div id="chat-wrapper">
            <div @if(request('blockId')) class="chat-block-id" @else class="chat-overlay" @endif id="chatbotPreviewModal">
                <div class="chat-dialog">
                    <div class="chat-header">
                        <div class="chat-header-info">
                            <img class="chat-header-avatar" src="{{$chatConfig?->character_url}}" alt="{{$botName}}">
                            <div class="chat-header-text">
                                <h3>{{$botName}}</h3>
                                <p>AI Assistant</p>
                            </div>
                        </div>
                        <div class="chat-header-actions">
                            <div class="chat-header-btn" id="chatCloseBtn">
                                <svg viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                            </div>
                        </div>
                    </div>
                    <div class="chat-body">
                        <div id="chat-bot" class="chat-content">
                            <div id="chat" class="chat-messages">
                                <div class="chat-message-group bot">
                                    <img class="chat-message-avatar" src="{{$chatConfig?->character_url}}" alt="{{$botName}}">
                                    <div class="chat-message-content">
                                        <div class="chat-message bot" id="chatbotGreeting">{{$chatConfig?->welcome_message ?? 'Hi there! How can I help you today?'}}</div>
                                        <div class="chat-message-meta">{{$botName}} &bull; AI Assistant &bull; Just now</div>
                                    </div>
                                </div>
                            </div>
                            <div id="typingIndicator" class="chat-message-group bot" style="display: none;">
                                <img class="chat-message-avatar" src="{{$chatConfig?->character_url}}" alt="{{$botName}}">
                                <div class="chat-message-content">
                                    <div class="chat-message bot typing-indicator">
                                        <div class="typing-dots">
                                            <span></span>
                                            <span></span>
                                            <span></span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="chat-input-area">
                        <div class="chat-input-wrapper">
                            <textarea rows="1" class="chat-input" placeholder="Ask a question..." id="userMessage"></textarea>
                            <button data-chat-config="{{$chatConfig?->uuid}}" id="sendMessageBtn" class="chat-send-btn">
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
            <div id="chatLauncher" class="chat-launcher">
                <div class="chat-launcher-btn">
                    <img class="launcher-open" src="{{$chatConfig?->character_url}}" alt="Open chat">
                    <svg class="launcher-close" viewBox="0 0 24 24"><path d="M19 6.41L17.59 5 12 10.59 6.41 5 5 6.41 10.59 12 5 17.59 6.41 19 12 13.41 17.59 19 19 17.59 13.41 12z"/></svg>
                </div>
            </div>
        </div>
    `;

    function insertNewMessage(msg, type) {
      const avatarUrl = '{{$chatConfig?->character_url}}';
      const botName = '{{$botName}}';
      let newMessage;
      const time = new Date().toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});

      if (type === 'user') {
        newMessage = `
          <div class="chat-message-group user">
            <div class="chat-message-content">
              <div class="chat-message user">${msg}</div>
              <div class="chat-message-meta">${time}</div>
            </div>
          </div>`;
      } else {
        newMessage = `
          <div class="chat-message-group bot">
            <img class="chat-message-avatar" src="${avatarUrl}" alt="${botName}">
            <div class="chat-message-content">
              <div class="chat-message bot">${msg.answer}</div>
              <div class="chat-message-meta">${botName} &bull; AI Assistant &bull; ${time}</div>
            </div>
          </div>`;
      }

      document.getElementById('typingIndicator').insertAdjacentHTML('beforebegin', newMessage);
      const chatElement = document.getElementById("chat-bot");
      chatElement.scrollTop = chatElement.scrollHeight;
    }

    function showTyping(show = true) {
      const indicator = document.getElementById('typingIndicator');
      indicator.style.display = show ? 'flex' : 'none';
      if (show) {
        const chatElement = document.getElementById("chat-bot");
        chatElement.scrollTop = chatElement.scrollHeight;
      }
    }

    // Append styles to head
    const styleSheet = document.createElement('style');
    styleSheet.type = 'text/css';
    styleSheet.innerText = styles;
    document.head.appendChild(styleSheet);

    // Append HTML to body
    const chatContainer = document.createElement('div');
    chatContainer.innerHTML = htmlTemplate;
    document.body.appendChild(chatContainer);
    let sendButton = document.getElementById('sendMessageBtn');

    // Auto-resize textarea
    const textarea = document.getElementById('userMessage');
    textarea.addEventListener('input', function() {
      this.style.height = 'auto';
      this.style.height = Math.min(this.scrollHeight, 120) + 'px';
    });

    // JavaScript functionality
    {
      document.getElementById("userMessage").addEventListener("keydown", function(event) {
        if (event.key === "Enter" && !event.shiftKey) {
          event.preventDefault();
          document.getElementById("sendMessageBtn").click();
        }
      });

      // Use chatConfig-specific key to isolate messages per user/chatbot
      const lastChatMessageIdKey = 'lastChatMessageId_{{$chatConfig->uuid}}';
      const chatOpenedKey = 'chatOpened_{{$chatConfig->uuid}}';
      let lastMessage = localStorage.getItem(lastChatMessageIdKey) ?
          JSON.parse(localStorage.getItem(lastChatMessageIdKey)) :
          null;


      sendButton.addEventListener('click', function() {
        const userMsg = document.getElementById('userMessage').value.trim();

        if (!userMsg) {
          return;
        }

        const chatConfig = this.getAttribute('data-chat-config');
        sendButton.disabled = true;
        document.getElementById('userMessage').value = '';
        document.getElementById('userMessage').style.height = 'auto';

        insertNewMessage(userMsg, 'user');
        showTyping(true);

        // Build request body - only include chatUuid if we have an existing conversation
        const requestBody = {
          message: userMsg,
          chatConfig: chatConfig,
        };
        if (lastMessage?.chat_uuid) {
          requestBody.chatUuid = lastMessage.chat_uuid;
        }

        fetch('{{route("api.chat.message")}}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
          },
          body: JSON.stringify(requestBody),
        }).then(response => {
          if (!response.ok) {
            return response.json().catch(() => ({ message: 'Request failed' })).then(err => {
              throw new Error(err.message || err.errors?.message?.[0] || 'Request failed');
            });
          }
          return response.json();
        }).then(data => {
          showTyping(false);

          if (data.error) {
            insertNewMessage({answer: data.message || 'Sorry, an error occurred.'}, 'server');
            sendButton.disabled = false;
            return;
          }

          lastMessage = data.message;
          localStorage.setItem(lastChatMessageIdKey, JSON.stringify(lastMessage));

          // Answer comes directly in response - no polling needed
          if (data.message && data.message.answer) {
            insertNewMessage(data.message, 'server');
          } else if (data.message && !data.message.processed) {
            // If not processed yet, poll for answer
            setTimeout(() => getAnswer(), 500);
          }
          sendButton.disabled = false;
        }).catch(error => {
          showTyping(false);
          sendButton.disabled = false;
          console.error('Chat error:', error);
          insertNewMessage({answer: error.message || 'Sorry, I could not process your request. Please try again.'}, 'server');
        });
      });

      function getAnswer(withHistory = false) {
        if (!lastMessage) {
          return;
        }
        let url = '{{route("api.chat.result")}}?messageUuid=' + lastMessage.message_uuid;
        if (withHistory) {
          url += '&with-history=1';
        }
        fetch(url, {
          headers: {
            'Accept': 'application/json',
          }
        }).then(response => {
              if (!response.ok) {
                // Clear stale localStorage if message not found
                localStorage.removeItem(lastChatMessageIdKey);
                lastMessage = null;
                showTyping(false);
                return null;
              }
              return response.json();
            }).then(data => {
              if (!data) return;
              let msg = data.message;

              if (data.history) {
                data.history.forEach(m => {
                  insertNewMessage(m.question, 'user');
                  if (m.answer) {
                    insertNewMessage(m, 'server');
                  }
                });

                if(data.history && data.history.length > 0) {
                  const msg = data.history[data.history.length - 1];
                  if (!msg.processed) {
                    showTyping(true);
                    setTimeout(getAnswer, 500);
                    return;
                  }
                }
                showTyping(false);
                return;
              }

              if (!msg.processed) {
                showTyping(true);
                setTimeout(getAnswer, 500);
                return;
              }
              showTyping(false);

              insertNewMessage(msg, 'server');

              sendButton.disabled = false;
            }).catch(error => {
              // Silently handle errors (e.g., old messages not found)
              showTyping(false);
              localStorage.removeItem(lastChatMessageIdKey);
              lastMessage = null;
            });
      }

      let chatOpened = false;
      const launcher = document.getElementById('chatLauncher');
      const closeBtn = document.getElementById('chatCloseBtn');

      window.toggleChatModal = function(isSaveState = true) {
        const modal = document.getElementById('chatbotPreviewModal');

        chatOpened = !chatOpened;
        modal.style.display = chatOpened ? 'block' : 'none';
        launcher.classList.toggle('opened', chatOpened);

        if(isSaveState) {
          localStorage.setItem(chatOpenedKey, chatOpened);
        }

        if (chatOpened) {
          document.getElementById('userMessage').focus();
        }
      };

      launcher.addEventListener('click', function() {
        toggleChatModal();
      });

      if (closeBtn) {
        closeBtn.addEventListener('click', function() {
          toggleChatModal();
        });
      }

      if (lastMessage) {
        if(localStorage.getItem(chatOpenedKey) === 'true') {
          toggleChatModal(false);
        }
        getAnswer(true);
      }

    @if(request('blockId'))
      const blockId = document.getElementById('{{ request("blockId") }}');
      const chatEmbed = document.querySelector('.chat-block-id');
      if (blockId) {
        blockId.insertAdjacentElement('beforeend', chatEmbed);
        chatEmbed.style.display = 'block';
      }
      // Hide launcher in block mode
      if (launcher) launcher.style.display = 'none';
    @endif
    }

  })();
