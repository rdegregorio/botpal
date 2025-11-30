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
            padding: 20px;
            border-radius: 5px;
            max-width: 435px;
            max-height: 50vh;
            height: 900px;
            overflow: auto;
            display: flex;
            flex-direction: column;
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
            background: url("{{url('icons/close-medium.svg')}}") no-repeat center #dcdcdc;
            transform: scale(1.1);
        }
        .chat-avatar:hover {
            opacity: 95%;
        }
        .chat-body {
          flex-grow: 1;
          display: flex;
          flex-direction: column;
        }
        .chat-content {
            height: 100px;
            overflow-y: auto;
            flex-grow: 1;
        }
        .chat-messages {
            margin-bottom: 10px;
        }
        .chat-message {
            margin-bottom: 10px;
        }
        .chat-message.message--user {
            background-color: {{$colorPrimary}};
            padding: 8px;
            border-radius: 10px;
            color: #FFF;
            float: right
        }
        .chat-message.message--server {
            background-color: {{$colorSecondary}};
            padding: 8px;
            border-radius: 10px;
            color: #212529;
            float: left
        }
        .chat-input-group {
            display: flex;
            justify-content: space-between;
            align-items: center;
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
    `;

    // HTML structure for the widget
    const htmlTemplate = `
        <div id="chat-wrapper">
            <div @if(request('blockId')) class="chat-block-id" @else class="chat-overlay" @endif id="chatbotPreviewModal">
                <div class="chat-dialog">
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
                              <img src="{{url('icons/typing-texting.gif')}}" width="50" alt="Typing..." />
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
                    Create your own AI chatbot with <a href="{{url('/')}}" target="_blank">iamsam.ai</a>
                </div>
                @endif
                </div>
            </div>
            <div id="chatAvatar" class="chat-avatar"><img src="{{$chatConfig?->character_url}}" width="{{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE)}}" /></div>
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

      const lastChatMessageIdKey = 'lastChatMessageId';
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

        insertNewMessage(userMsg, 'user');

        setTimeout(showTyping, 250);

        fetch('{{route("api.chat.message")}}', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            message: userMsg,
            chatConfig: chatConfig,
            chatUuid: lastMessage?.chat_uuid || null,
            messageUuid: lastMessage?.message_uuid || null,
          }),
        }).then(response => response.json()).then(data => {
          lastMessage = data.message;
          localStorage.setItem(lastChatMessageIdKey, JSON.stringify(lastMessage));
          document.getElementById('userMessage').value = '';
          getAnswer();
        }).catch(error => {
          alert('error');
        });
      });

      function getAnswer(withHistory = false) {
        if (!lastMessage) {
          return;
        }
        let url = '{{route("api.chat.message")}}?messageUuid=' + lastMessage.message_uuid;
        if (withHistory) {
          url += '&with-history=1';
        }
        fetch(url).
            then(response => response.json()).
            then(data => {
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
            }).
            catch(error => {
              alert('error answer');
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
          localStorage.setItem('chatOpened', chatOpened);
        }
      };

      avatarButton.addEventListener('click', function() {
        toggleChatModal();
      });

      if (lastMessage) {
        if(localStorage.getItem('chatOpened') === 'true') {
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