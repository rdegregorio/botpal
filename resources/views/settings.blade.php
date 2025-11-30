@php
    /** @var \App\Models\ChatConfig $chatConfig */
@endphp
@php($fontSize = $chatConfig?->getSettings(\App\Models\ChatConfig::SETTINGS_FONT_SIZE) ?? 15)
@php($fontFamily = $chatConfig?->getSettings(\App\Models\ChatConfig::SETTINGS_FONT_FAMILY) ?? 'Tahoma')

@extends('layouts.dashboard')

@section('page-title', 'Chatbot Appearance')

@section('content')
    <div class="dashboard-card">
                <div class="row align-items-center mb-2">
                    <div class="col-md-2 col-12">
                        <p class="mb-3">Character:</p>
                    </div>
                    <div class="col-md-10 col-12">
                        <div class="container mb-4" style="max-width: 65em;">
                            @include('_characters')
                        </div>
                    </div>
                </div>
                <div class="row align-items-center mb-3">
                    <div class="col-md-2 col-12"> Welcome Message: </div>
                    <div class="col-md-10 col-12">
                        <div class="form-container position-relative" data-editable> <input readonly name="welcome_message" value="{{$chatConfig?->welcome_message ?? "Hi there! I'm Ben an AI support agent. How can I help?"}}" type="text" class="form-control" id="editableField" readonly> </div>
                    </div>
                </div>

                @php($characterSize = (int) ($chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE) ?? 80))
                <div class="row align-items-center mb-2">
                    <div class="col-md-2 col-12 mt-4"> Character Size: </div>
                    <div class="col-md-10 col-12 mt-4">
                        <button @class(['btn btn-outline-custom size-btn', 'active' => $characterSize === 70]) data-bs-toggle="tooltip" data-size="70">70px</button>
                        <button @class(['btn btn-outline-custom size-btn', 'active' => $characterSize === 80]) data-bs-toggle="tooltip" data-size="80">80px</button>
                        <button @class(['btn btn-outline-custom size-btn', 'active' => $characterSize === 90]) data-bs-toggle="tooltip" data-size="90">90px</button>
                        <button @class(['btn btn-outline-custom size-btn', 'active' => $characterSize === 100]) data-bs-toggle="tooltip" data-size="100">100px</button>
                    </div>
                </div>
                @push('bottom')
                    <script>
                        $(document).ready(function() {
                            // Initialize tooltips with dynamic content
                            var tooltipTriggerList = [].slice.call(document.querySelectorAll('.size-btn'));
                            tooltipTriggerList.forEach(function(tooltipTriggerEl) {
                                new bootstrap.Tooltip(tooltipTriggerEl, {
                                    title: function() {
                                        var size = $(this).data('size');
                                        var selectedCharacterSrc = $('.selected-image').attr('src');
                                        return `<img src="${selectedCharacterSrc}" class="tooltip-image" width="${size}" height="${size}">`;
                                    },
                                    html: true,
                                    customClass: 'character-size-tooltip'
                                });
                            });

                            // Toggle 'active' class on button click and hide tooltip
                            $('.size-btn').click(function() {
                                $('.size-btn').removeClass('active');
                                $(this).addClass('active');
                                updateChatConfig({target: {name: 'settings[{{\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE}}]', value: $(this).data('size')}});
                                // Hide the tooltip after the button is clicked
                                $(this).tooltip('hide');
                            });
                        });
                    </script>
                @endpush


                @php($chatPlacement = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHAT_PLACEMENT))
                <div class="row align-items-center mb-3">
                    <div class="col-md-2 col-12 mt-4"> Chat Placement: </div>
                    <div class="col-md-10 col-12 mt-4">
                        <button @class(['btn btn-outline-custom', 'active' => $chatPlacement === 'left']) data-placement="left">
                            <i class="bi-arrow-down-left-square"></i>&nbsp;Left
                        </button>
                        <button @class(['btn btn-outline-custom', 'active' => $chatPlacement === 'right']) data-placement="right">
                            <i class="bi-arrow-down-right-square"></i>&nbsp;Right
                        </button>
                    </div>
                </div>
                @push('bottom')
                    <script>
                      $(document).ready(function() {
                        $('button[data-placement]').click(function() {
                          $('button[data-placement]').removeClass('active');
                          $(this).addClass('active');
                          updateChatConfig({target: {name: 'settings[{{\App\Models\ChatConfig::SETTINGS_CHAT_PLACEMENT}}]', value: $(this).data('placement')}});
                        });
                      });
                    </script>
                @endpush

                @php($colorPrimary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_PRIMARY))
                @php($colorSecondary = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_SECONDARY))
                @php($colorCharacterBg = $chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_CHARACTER_BG))
                <div class="row align-items-center mb-3">
                    <div class="col-md-2 col-12 mt-4"> Chat Colors: </div>
                    <div class="col-md-10 col-12">
                        <div class="color-box-container mt-3 d-flex">
                            <div data-name="settings[{{\App\Models\ChatConfig::SETTINGS_COLOR_PRIMARY}}]" class="color-display-box" id="colorDisplayBox1" title="Click to pick a color" style="background-color: {{$colorPrimary}};">
                                <span id="colorBoxText" style="color: #212529">Primary</span>
                            </div>
                            <div data-name="settings[{{\App\Models\ChatConfig::SETTINGS_COLOR_SECONDARY}}]" class="color-display-box" id="colorDisplayBox2" title="Click to pick a color" style="background-color: {{$colorSecondary}}; margin: 0 5px;">
                                <span id="colorBoxText2" style="color: #212529">Secondary</span>
                            </div>
                            <div data-name="settings[{{\App\Models\ChatConfig::SETTINGS_COLOR_CHARACTER_BG}}]" class="color-display-box" id="colorDisplayBox3" title="Click to pick a color" style="background-color: {{$colorCharacterBg}}; margin-right: 10px;">
                                <span id="colorBoxText2" style="color: #212529">Character BG</span>
                            </div>
                            <input type="color" class="colorPicker" id="colorPicker1" value="{{$colorPrimary}}">
                            <input type="color" class="colorPicker" id="colorPicker2" value="{{$colorSecondary}}">
                            <input type="color" class="colorPicker" id="colorPicker3" value="{{$colorCharacterBg}}">
                            <button id="livePreviewBtn" class="btn btn-sm btn-livepreview" data-bs-toggle="modal" data-bs-target="#settingsModal"> <i class="bi bi-eye"></i></button>
                        </div>
                    </div>
                </div>
                @push('bottom')
                    <script>

                      let colorTimeout;
                      let $colorDisplayBox;
                      window.colorSecondary = '{{$colorSecondary}}';
                      window.colorCharacterBg = '{{$colorCharacterBg}}';
                      window.fontSize = '{{$fontSize}}';
                      window.fontFamily = '{{$fontFamily}}';

                      $('#colorDisplayBox1, #colorDisplayBox2, #colorDisplayBox3').click(function(e) {
                        $colorDisplayBox = $(this);
                        if($colorDisplayBox.attr('id') === 'colorDisplayBox1') {
                          $('#colorPicker1').click();
                        } else if ($colorDisplayBox.attr('id') === 'colorDisplayBox2') {
                          $('#colorPicker2').click();
                        } else {
                          $('#colorPicker3').click();
                        }
                      });

                      $('#colorPicker1, #colorPicker2, #colorPicker3').on('input', function() {
                        var selectedColor = $(this).val();
                        $colorDisplayBox.css('background-color', selectedColor);

                        if(this.getAttribute('id') === 'colorPicker2') {
                          window.colorSecondary = selectedColor;
                        } else if(this.getAttribute('id') === 'colorPicker3') {
                          window.colorCharacterBg = selectedColor;
                        }

                        updatePreview();

                        clearTimeout(colorTimeout);
                        colorTimeout = setTimeout(function() {
                          updateChatConfig({target: {name: $colorDisplayBox.data('name'), value: selectedColor}});
                        }, 1000);
                      });

                      function updatePreview() {
                        const color = $('#colorPicker1').val();

                        $('.btn-chat').css({
                          'background-color': color,
                          'border-color': color
                        });
                        $('#settingsModal .message--user').css('background-color', color);
                        $('#settingsModal .message--server').css('background-color', window.colorSecondary);
                        $('#settingsModal #avatarImage').css('background-color', window.colorCharacterBg);
                        $('#settingsModal').css('font-family', window.fontFamily);
                        $('#settingsModal .message--user, #settingsModal .message--server, #settingsModal #userMessage').css('font-size', window.fontSize + 'px');

                        $('.btn-chat').hover(
                            function() { $(this).css('background-color', color); },
                            function() { $(this).css('background-color', color); }
                        ).focus(function() {
                          $(this).css('background-color', color);
                        }).blur(function() {
                          $(this).css('background-color', color);
                        });
                      }
                    </script>
                @endpush

                <div class="row align-items-center mb-3">
                    <div class="col-md-2 col-12 mt-4"> Font Size: </div>
                    <div class="col-md-10 col-12 mt-4">
                        <input type="range" id="fontSizeSlider" min="10" max="24" value="{{$fontSize}}">
                        <span id="fontSizePreview" style="font-size: {{$fontSize}}px; font-family: {{$fontFamily}};">&nbsp;&nbsp;Sample Text</span>
                    </div>
                </div>

                <div class="row mb-3 align-items-center">
                    <div class="col-md-2 col-12 mt-4">Font Family:</div>
                    <div class="col-md-10 col-12 mt-4">
                        <select data-value="" onfocus="this.setAttribute('data-value', this.value);"
                                id="fontFamilySelector" class="form-select">
                            <option @selected($fontFamily === "Arial") value="Arial" style="font-family: Arial;">Arial</option>
                            <option @selected($fontFamily === "Helvetica") value="Helvetica" style="font-family: Helvetica;">Helvetica</option>
                            <option @selected($fontFamily === "Times New Roman") value="Times New Roman" style="font-family: 'Times New Roman';">Times New Roman</option>
                            <option @selected($fontFamily === "Georgia") value="Georgia" style="font-family: Georgia;">Georgia</option>
                            <option @selected($fontFamily === "Courier New") value="Courier New" style="font-family: 'Courier New';">Courier New</option>
                            <option @selected($fontFamily === "Verdana") value="Verdana" style="font-family: Verdana;">Verdana</option>
                            <option @selected($fontFamily === "Tahoma") value="Tahoma" style="font-family: Tahoma;">Tahoma</option>
                            <option @selected($fontFamily === "Trebuchet MS") value="Trebuchet MS" style="font-family: 'Trebuchet MS';">Trebuchet MS</option>
                            <option @selected($fontFamily === "Palatino Linotype") value="Palatino Linotype" style="font-family: 'Palatino Linotype';">Palatino Linotype</option>
                            <option @selected($fontFamily === "Book Antiqua") value="Book Antiqua" style="font-family: 'Book Antiqua';">Book Antiqua</option>
                            <option @selected($fontFamily === "Garamond") value="Garamond" style="font-family: Garamond;">Garamond</option>
                            <option @selected($fontFamily === "Century Gothic") value="Century Gothic" style="font-family: 'Century Gothic';">Century Gothic</option>
                            <option @selected($fontFamily === "Calibri") value="Calibri" style="font-family: Calibri;">Calibri</option>
                        </select>
                    </div>
                </div>
                <div class="row align-items-center mt-2">
                    <div class="col-md-2 col-12"> Summary: </div>
                    <div class="col-md-10 col-12">
                        <div class="form-group mb-4" data-editable> <textarea readonly name="general_prompt" class="form-control" id="exampleTextarea" rows="5" placeholder="'e.g. Our company is called Lumina. We specialize in artisanal candles, handcrafted to perfection. Based in the US, Lumina offers a diverse range of candles, from enchanting scents to timeless classics. Each candle promises to illuminate your space with elegance and warmth. Dive into a world of ambient glow and mesmerizing aromas with Lumina." style="background-color: #F4F4F7;">{{$chatConfig?->general_prompt}}</textarea> </div>
                    </div>
                </div>
    </div>
@endsection

@push('bottom')
    <script>
      $(function(e) {
        $('input:read-only, textarea:read-only').click(function () {
          $(this).closest('[data-editable]')?.find('[data-edit]')?.click();
        });
        $('[data-editable]').mouseleave(function () {
          if ($(this).data('edit-mode')) {
            return;
          }
          $(this).find('.edit-buttons').remove();
        });

        $('[data-editable]').mouseover(function () {

          if ($(this).find('.edit-buttons').length) {
            return;
          }

          var $buttons = $('\n' +
              '<div class="edit-buttons">\n' +
              '    <button data-edit class="btn btn-sm btn-primary">Edit</button>\n' +
              '</div>');

          $(this).append($buttons);
        });

        $(document).on('click', '[data-editable] [data-edit]', function (e) {

          var $block = $(this).closest('[data-editable]');

          $block.data('edit-mode', true);
          $block.find('input, textarea').prop('readonly', false).focus();

          var buttons = '<button data-save class="btn btn-sm btn-success">Save</button>\n' +
              '<button data-cancel class="btn btn-sm btn-default">Cancel</button>';

          $block.find('.edit-buttons').html(buttons);
        });

        $(document).on('click', '[data-editable] [data-cancel]', function () {
          var $block = $(this).closest('[data-editable]');
          $block.data('edit-mode', false);
          $block.find('input, textarea').prop('readonly', true);

          $block.find('.edit-buttons').remove();
        });

        $(document).on('click', '[data-editable] [data-save]', function () {
          var $block = $(this).closest('[data-editable]');
          $block.data('edit-mode', false);
          $block.find('input, textarea').prop('readonly', true);
          updateChatConfig({target: $block.find('input, textarea').get(0)});
        });
      });
    </script>
    <script>
      $(document).ready(function(){
        // Font Size Slider
        $('#fontSizeSlider').on('input', function(e) {
          var selectedFontSize = $(this).val() + 'px';
          $('#fontSizePreview').css('font-size', selectedFontSize);

            window.fontSize = $(this).val();
            updatePreview();
            updateChatConfig({target: {name: 'settings[{{\App\Models\ChatConfig::SETTINGS_FONT_SIZE}}]', value: $(this).val()}});
        });

        // Font Family Selector
        $('#fontFamilySelector').on('change', function(e) {
          var selectedFontFamily = $(this).val();
          $('#fontSizePreview').css('font-family', selectedFontFamily);

          window.fontFamily = $(this).val();
          updatePreview();
          updateChatConfig({target: {name: 'settings[{{\App\Models\ChatConfig::SETTINGS_FONT_FAMILY}}]', value: $(this).val()}});
        });
      });
    </script>
    <script>
        @if($chatConfig?->character)
        $('[data-character="{{$chatConfig->character}}"]').addClass('selected-image');
        @endif

        function debounce(func, delay) {
          let debounceTimer;
          return function() {
            const context = this;
            const args = arguments;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(() => func.apply(context, args), delay);
          };
        }

        const debouncedUpdates = {};

        function updateChatConfig(e) {
          const {name, value} = e.target;
          if (!debouncedUpdates[name]) {
            debouncedUpdates[name] = debounce(function(name, value) {
              $.post('{{ route('dashboard.update-chat-config') }}', {[name]: value, _token: '{{ csrf_token() }}'});
            }, 1000);
          }
          debouncedUpdates[name](name, value);
        }

        $(document).ready(function() {

            // Show or hide the edit button based on hover and readonly state
            $('.form-container').hover(
                function() {
                    if ($('#editableField').prop('readonly')) {
                        $('#editButton').show();
                    }
                },
                function() {
                    if ($('#editableField').prop('readonly')) {
                        $('#editButton').hide();
                    }
                }
            );

            // Enable editing when the input field is clicked
            $('#editableField').click(function() {
                $(this).prop('readonly', false);
                $('#editButton').text('Save').removeClass('btn-edit').addClass('btn-save');
            });

            // Save the input when Enter key is pressed
            $('#editableField').keyup(function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    saveInput();
                }
            });

            // Toggle between edit and save states when the button is clicked
            $('#editButton').click(function(event) {
                event.stopPropagation(); // Prevent triggering the document click event
                if ($('#editableField').prop('readonly')) {
                    $('#editableField').prop('readonly', false);
                    $(this).text('Save').removeClass('btn-edit').addClass('btn-save');
                } else {
                    saveInput();
                }
            });

            // Save the input when clicked outside the form-container
            $(document).click(function(event) {
                if (!$(event.target).closest('.form-container').length) {
                    saveInput();
                }
            });

            function saveInput() {
                var $input = $('#editableField');
                if (!$input.prop('readonly')) {
                    $input.prop('readonly', true);
                    $('#editButton').hide().text('Edit').removeClass('btn-save').addClass('btn-edit');
                    $('#chatbotGreeting').text($input.val());
                }
            }

            $('#sendMessageBtn').click(function() {
                var userMsg = $('#userMessage').val();
                if (userMsg.trim() !== "") {
                    $('#chat').append('<div class="message-wrapper"><div class="message mb-2 message--user" style="font-size:' + window.fontSize + 'px; background-color:' + $('#colorPicker1').val() + '">' + userMsg + '</div><div class="clearfix"></div></div>');
                    $('#userMessage').val('');

                    setTimeout(function() {
                        $('#chat').append('<div class="message-wrapper"><div class="message mb-2 message--server" style="font-size:' + window.fontSize + 'px;background-color:' + window.colorSecondary + '">Thanks for your message! I\'m just a preview, but I\'m here to help.</div><div class="clearfix"></div></div>');
                    }, 1000);
                }
            });

            $('#userMessage').keyup(function(e) {
                if (e.key === 'Enter' || e.keyCode === 13) {
                    $('#sendMessageBtn').click();
                }
            });

            let images = document.querySelectorAll('.image-character');

            images.forEach(function(img) {
                img.addEventListener('click', function(e) {
                    images.forEach(function(innerImg) {
                        innerImg.classList.remove('selected-image');
                    });
                    img.classList.add('selected-image');
                    let characterName = img.getAttribute('data-character-name');
                    updateChatbotGreeting(characterName);
                    updateChatConfig({target: {name: 'character', value: img.getAttribute('data-character')}});
                });
            });

            function updateChatbotGreeting(characterName) {
                let greeting = "Hi there! I'm " + characterName + ", an AI support agent. How can I help?";
                $('#chatbotGreeting').text(greeting);
                let newImageSrc = $('.selected-image').attr('src') || "/images/ch3.png"; // default to Ben's image
                $('#avatarImage').attr('src', newImageSrc);
            }

        });

        $('#chatCodeBox').click(function() {
            var range = document.createRange();
            range.selectNode(document.getElementById("chatCodeBox"));
            window.getSelection().removeAllRanges();
            window.getSelection().addRange(range);
            document.execCommand("copy");
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'bottom'
            });
        });
    </script>
    <script>
        $('#chatCodeBox').click(function() {
            var range = document.createRange();
            range.selectNode(document.getElementById("chatCodeBox"));
            window.getSelection().removeAllRanges(); // Clear current selection
            window.getSelection().addRange(range); // Select the code box content
            document.execCommand("copy"); // Copy the selected content
            // Note: No modal or alert after copying
        });
    </script>
    <script>
        // Initialize Bootstrap Tooltip with placement bottom
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'bottom' // Set the placement to bottom
            })
        })
    </script>
    <script>
        $(document).ready(function(){
            // Adding a click event listener to all the images with the 'image-character' class
            $('.image-character').on('click', function(e) {
                // Fetching the character name from the clicked image's data attribute
                var characterName = $(this).data('character-name');

                // Updating the input field's value with the new message
                $('#editableField').val(`Hi there! I'm ${characterName} an AI support agent. How can I help?`);
                updateChatConfig({target: $('#editableField').get(0)});
            });
        });

    </script>
    <div class="modal fade" id="settingsModal" style="font-family: '{{$fontFamily}}'" tabindex="-1" aria-labelledby="settingsModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <!-- Use modal-lg for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">
                        <div class="row align-items-center">
                            <div class="col-auto"> <img id="avatarImage" src="{{$chatConfig->character_url}}" data-character-name="Ben" alt="Ben" width="{{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_CHARACTER_SIZE)}}" style="background-color: {{$chatConfig->getSettings(\App\Models\ChatConfig::SETTINGS_COLOR_CHARACTER_BG)}}; border-radius: 50%"> </div>
                            <div class="col">
                                <div class="message--server" style="font-size: {{$fontSize}}px; background-color: {{$colorSecondary}}; padding: 5px 10px; border-radius: 10px" id="chatbotGreeting">{{$chatConfig?->welcome_message ?? ' Hi there! I\'m Ben an AI support agent. How can I help?'}} </div>
                            </div>
                        </div>
                    </div> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column" style="height: 400px;">
                    <!-- Use flexbox utilities -->
                    <div id="chat-bot" class="flex-grow-1 overflow-auto">
                        <!-- Allow this div to grow and take available space -->
                        <div id="chat" class="message-area mb-3">
                            <!-- Placeholder for future messages -->
                        </div>
                    </div>
                    <div class="input-group mt-2">
                        <!-- Add a margin-top for some spacing --> <textarea type="text" style="font-size:{{$fontSize}}px;" class="form-control" placeholder="Type your message here..." rows="1" id="userMessage"></textarea> <button class="btn btn-chat" style="letter-spacing: 1px; background:{{$colorPrimary}};" id="sendMessageBtn">Send</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush
