@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $colorPrimary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_PRIMARY);
    $colorSecondary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_SECONDARY);
    $chatPlacement = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHAT_PLACEMENT);
    $characterSize = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE);
@endphp
  (function() {

    // CSS styles for the widget
    const styles = `
        #chat-wrapper * {
          font-family: {{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_FONT_FAMILY)}} !important;
          box-sizing: border-box;
        }
        #chat-wrapper *::-webkit-scrollbar {
          width: 9px;
        }
        #chat-wrapper *::-webkit-scrollbar-track {
          background: transparent;
        }
        #chat-wrapper *::-webkit-scrollbar-thumb {
          background-color: rgba(155, 155, 155, 0.5);
          border-radius: 20px;
          border: transparent;
        }
        .chat-grid-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            margin-top: 0px;
            gap: 15px;
        }
        
        .chat-block-id {
            display: none;
        }
        
        .chat-message-wrapper, .chat-input {
            font-size: {{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_FONT_SIZE)}}px;
            font-family: {{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_FONT_FAMILY)}} !important;
        }
        
        .chat-block-id .chat-dialog {
            position: relative;
            left: auto;
            right: auto;
            bottom: auto;
            top: auto;
            width: 100%;
            max-width: none;
            max-height: none;
            overflow: visible;
            transform: none;
            padding: 15px;
            padding-top: 40px;
        }

        .chat-block-id #expandChatBtn {
            display: none;
        }

        .chat-block-id .chat-content {
            max-height: 400px;
            overflow-y: auto;
        }
        
        .chat-grid-col, .chat-grid-col-auto {
            position: relative;
            width: 100%;
            flex-basis: 0;
            flex-grow: 1;
            max-width: 100%;
        }
        
        .chat-grid-col-auto {
            flex: 0 0 auto;
            width: auto;
            max-width: none;
        }

        .chat-overlay {
            display: none;
            position: fixed;
            bottom: calc({{$characterSize}}px + 40px);
            @if($chatPlacement == 'left')
            left: 20px;
            @else
            right: 20px;
            @endif
            z-index: 1000;
            border: 1px solid #ccc;
            border-radius: 5px;
            max-width: 90%;
        }
        .chat-dialog {
            background-color: white;
            padding: 45px 20px 20px 20px;
            border-radius: 8px;
            max-width: 480px;
            max-height: 60vh;
            height: 900px;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            position: relative;
        }
        .chat-close {
            position: absolute;
            right: 10px;
            top: 5px;
            cursor: pointer;
            font-size: 20px;
            font-weight: 700;
        }
        .chat-avatar img {
          border-radius: 50%;
          object-fit: cover;
          user-select: none;
        }
        .chat-avatar {
            position: fixed;
            bottom: 20px;
            @if($chatPlacement == 'left')
            left: 20px;
            @else
            right: 20px;
            @endif
            cursor: pointer;
            border-radius: 50%;
            background-color: {{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_CHARACTER_BG)}};
        }
        .chat-avatar--opened {
            transform: scale(.7);
        }
        .chat-avatar--opened:after {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            border-radius: 50%;
            background: url("{{url('site-icons/close-medium.svg')}}") no-repeat center center #dcdcdc;
            background-size: 50% 50%;
            transform: scale(1.1);
        }
        .chat-avatar:hover {
            opacity: 95%;
        }
        .chat-body {
          flex: 1 1 auto;
          display: flex;
          flex-direction: column;
          min-height: 0;
          overflow: hidden;
        }
        .chat-content {
            min-height: 0;
            overflow-y: auto;
            flex: 1 1 auto;
            padding-right: 8px;
        }
        .chat-messages {
            margin-bottom: 10px;
        }
        .chat-message {
            margin-bottom: 12px;
        }
        .chat-message.message--user {
            background-color: {{$colorPrimary}};
            padding: 10px 12px;
            border-radius: 10px;
            color: #FFF;
            float: right;
            max-width: 85%;
        }
        .chat-message.message--server {
            background-color: {{$colorSecondary}};
            padding: 10px 12px;
            border-radius: 10px;
            color: #212529;
            float: left;
            max-width: 85%;
        }
        .chat-input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-shrink: 0;
            margin-top: 10px;
        }
        .chat-input:focus {
            outline: none;
        }
        .chat-input {
            flex: 1;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-right: 10px;
        }
        .chat-send-btn {
            padding: 10px 20px;
            background-color: {{$colorPrimary}};
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }
        .chat-clearfix {
            clear: both
        }
        .chat-copy {
          text-align: center;
          font-size: 12px;
          margin-top: 12px;
          color: #888888;
        }
        .chat-copy a {
          font-size: 12px;
          text-decoration: none;
          color: {{\App\Models\ChatConfig::DEFAULT_COLOR}};
        }
        .typing-indicator {
          display: flex;
          align-items: center;
          padding: 12px 16px;
          background: {{$colorSecondary}};
          border-radius: 10px;
          width: fit-content;
          gap: 4px;
        }
        .typing-dot {
          width: 8px;
          height: 8px;
          background-color: #666;
          border-radius: 50%;
          animation: typing-bounce 1.4s infinite ease-in-out both;
        }
        .typing-dot:nth-child(1) {
          animation-delay: -0.32s;
        }
        .typing-dot:nth-child(2) {
          animation-delay: -0.16s;
        }
        .typing-dot:nth-child(3) {
          animation-delay: 0s;
        }
        @keyframes typing-bounce {
          0%, 80%, 100% {
            transform: scale(0.6);
            opacity: 0.5;
          }
          40% {
            transform: scale(1);
            opacity: 1;
          }
        }
        .chat-header-actions {
          position: absolute;
          top: 10px;
          right: 10px;
          display: flex;
          gap: 8px;
          z-index: 10;
        }
        .chat-header-btn {
          width: 28px;
          height: 28px;
          border: none;
          background: #f0f0f0;
          border-radius: 4px;
          cursor: pointer;
          display: flex;
          align-items: center;
          justify-content: center;
          transition: background-color 0.2s;
        }
        .chat-header-btn:hover {
          background: #e0e0e0;
        }
        .chat-header-btn svg {
          width: 16px;
          height: 16px;
          fill: #666;
        }
        .chat-dialog.chat-expanded {
          max-width: 650px;
          max-height: 75vh;
        }
        .chat-title {
          margin-bottom: 15px;
        }
    `;

    // HTML structure for the widget
    const htmlTemplate = `
        <div id="chat-wrapper">
            <div @if(request('blockId')) class="chat-block-id" @else class="chat-overlay" @endif id="chatbotPreviewModal">
                <div class="chat-dialog" id="chatDialog">
                    <div class="chat-header-actions">
                        <button class="chat-header-btn" id="refreshChatBtn" title="Clear chat">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"><path d="M17.65 6.35A7.958 7.958 0 0012 4c-4.42 0-7.99 3.58-7.99 8s3.57 8 7.99 8c3.73 0 6.84-2.55 7.73-6h-2.08A5.99 5.99 0 0112 18c-3.31 0-6-2.69-6-6s2.69-6 6-6c1.66 0 3.14.69 4.22 1.78L13 11h7V4l-2.35 2.35z"/></svg>
                        </button>
                        <button class="chat-header-btn" id="expandChatBtn" title="Expand chat">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" id="expandIcon"><path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/></svg>
                        </button>
                    </div>
                    <div class="chat-body">
                        <div id="chat-bot" class="chat-content">
                          <div id="chat" class="chat-messages">
                            <div class="chat-title">
                                <div class="chat-grid-row">
                                    <div class="chat-grid-col-auto">
                                        <img id="avatarImage" src="{{$chatConfig?->character_url}}" alt="Chat bot" width="{{$characterSize}}" style="background-color: {{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_CHARACTER_BG)}}; border-radius: 50%">
                                    </div>
                                    <div class="chat-grid-col">
                                        <div class="chat-message-wrapper" style="background-color: {{$colorSecondary}};; padding: 5px 10px; border-radius: 10px" id="chatbotGreeting">{{$chatConfig?->welcome_message ?? ' Hi there! I\'m an AI support agent. How can I help?'}}</div>
                                    </div>
                                </div>
                            </div>
                          
                          <div id="typingIndicator" style="display: none;">
                              <div class="typing-indicator">
                                  <div class="typing-dot"></div>
                                  <div class="typing-dot"></div>
                                  <div class="typing-dot"></div>
                              </div>
                          </div>
                        </div>
                        </div>
                        <div class="chat-input-group">
                            <textarea rows="1" class="chat-input" placeholder="Type your message here..." id="userMessage"></textarea>
                            <button data-chat-config="{{$chatConfig?->uuid}}" id="sendMessageBtn" class="chat-send-btn">Send</button>
                        </div>
                    </div>
                @if($chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COPYRIGHT_ENABLED))
                <div class="chat-copy">
                    Create your own AI chatbot with <a href="{{url('/')}}" target="_blank">aisupport.bot</a>
                </div>
                @endif
                </div>
            </div>
            @if(!request('blockId'))<div id="chatAvatar" class="chat-avatar"><img src="{{$chatConfig?->character_url}}" width="{{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE)}}" /></div>@endif
        </div>
    `;

    function insertNewMessage(msg, type) {

      let newMessage;

      if (type === 'user') {
        newMessage = '<div class="chat-message-wrapper"><div class="chat-message mb-2 message--user">' +
            msg + '</div><div class="chat-clearfix"></div></div>';
      } else {
        newMessage = '<div class="chat-message-wrapper"><div class="chat-message mb-2 message--server">' +
            msg.answer;

        if (msg.source_documents?.ids?.length > 0) {
          newMessage += `<br><br><strong>Prompt tokens</strong>: ${msg.prompt_tokens}<br> <strong>Completion tokens</strong>: ${msg.completion_tokens}<br> <strong>Total tokens</strong>: ${msg.total_tokens}<br>`;
          newMessage += `<br><br><pre>${JSON.stringify(msg.source_documents, null,
              2)}</pre><br>`;
        }

        newMessage += '</div><div class="chat-clearfix"></div></div>';
      }

      document.getElementById('typingIndicator').
          insertAdjacentHTML('beforebegin', newMessage);

      const chatElement = document.getElementById("chat-bot");
      chatElement.scrollTop = chatElement.scrollHeight;
    }
    function showTyping(show = true) {
      document.getElementById('typingIndicator').style.display = show ? 'block' : 'none';
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
      const chatExpandedKey = 'chatExpanded_{{$chatConfig->uuid}}';
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

      const avatarButton = document.getElementById('chatAvatar');

      window.toggleChatModal = function(isSaveState = true) {
        const modal = document.getElementById('chatbotPreviewModal');

        chatOpened = !chatOpened;
        modal.style.display = chatOpened ? 'block' : 'none';

        avatarButton.classList.toggle('chat-avatar--opened', chatOpened);
        if(isSaveState) {
          localStorage.setItem(chatOpenedKey, chatOpened);
        }
      };

      avatarButton.addEventListener('click', function() {
        toggleChatModal();
      });

      // Refresh/Clear chat button
      const refreshBtn = document.getElementById('refreshChatBtn');
      refreshBtn.addEventListener('click', function() {
        // Clear all messages except the welcome message
        const chatMessages = document.getElementById('chat');
        const welcomeMessage = chatMessages.querySelector('.chat-title');
        chatMessages.innerHTML = '';
        if (welcomeMessage) {
          chatMessages.appendChild(welcomeMessage);
        }
        // Re-add the typing indicator
        const typingIndicator = document.createElement('div');
        typingIndicator.id = 'typingIndicator';
        typingIndicator.style.display = 'none';
        typingIndicator.innerHTML = '<div class="typing-indicator"><div class="typing-dot"></div><div class="typing-dot"></div><div class="typing-dot"></div></div>';
        chatMessages.appendChild(typingIndicator);

        // Clear localStorage and reset lastMessage
        localStorage.removeItem(lastChatMessageIdKey);
        lastMessage = null;
      });

      // Expand/Collapse chat button
      const expandBtn = document.getElementById('expandChatBtn');
      const chatDialog = document.getElementById('chatDialog');
      const expandIcon = document.getElementById('expandIcon');

      // Restore expanded state from localStorage
      if (localStorage.getItem(chatExpandedKey) === 'true') {
        chatDialog.classList.add('chat-expanded');
        expandIcon.innerHTML = '<path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>';
      }

      expandBtn.addEventListener('click', function() {
        const isExpanded = chatDialog.classList.toggle('chat-expanded');
        localStorage.setItem(chatExpandedKey, isExpanded);

        // Toggle icon between expand and collapse
        if (isExpanded) {
          expandIcon.innerHTML = '<path d="M5 16h3v3h2v-5H5v2zm3-8H5v2h5V5H8v3zm6 11h2v-3h3v-2h-5v5zm2-11V5h-2v5h5V8h-3z"/>';
        } else {
          expandIcon.innerHTML = '<path d="M7 14H5v5h5v-2H7v-3zm-2-4h2V7h3V5H5v5zm12 7h-3v2h5v-5h-2v3zM14 5v2h3v3h2V5h-5z"/>';
        }
      });

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
    @endif
    }

  })();
