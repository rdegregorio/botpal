@php
    /** @var \App\Models\ChatConfig $chatConfig */
@endphp
@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container">
            <div class="row">
                <div class="col-lg-10 col-12  mx-auto">
                    <h4 class="mb-0"><i class="bi bi-chat-dots"></i> Messages</h4>
                    <div class="content-wrapper-2">
                        <div class="container">
                            <div class="row mt-4">
                                <!-- Message Tabs -->
                                <ul class="nav nav-tabs">
                                    <li class="nav-item"><a class="nav-link active" href="#inbox" data-tab-type="inbox"
                                                            data-bs-toggle="tab">Inbox
                                            <span class="badge bg-danger">{{$totalMessages}}</span></a></li>
                                    <li class="nav-item"><a class="nav-link" href="#archived" @paid @paidUser data-tab-type="archived" data-bs-toggle="tab" @endpaidUser>Archived</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#flagged" @paid @paidUser data-tab-type="flagged" data-bs-toggle="tab" @endpaidUser>Flagged</a>
                                    </li>
                                    <li class="nav-item"><a class="nav-link" href="#deleted" @paid @paidUser data-tab-type="deleted" data-bs-toggle="tab" @endpaidUser>Deleted</a>
                                    </li>
                                </ul> <!-- Message Content -->
                                <div class="tab-content mt-4">
                                    <div class="tab-pane active">
                                    </div>
                                </div>
                                @freeUser
                                @if($totalMessages && $totalMessages > 1)
                                <div class="d-flex justify-content-between align-items-start mb-3 border p-3 rounded message-item" data-message-uid="331b3c4e-cc88-412c-903b-eb222bf0a93b" style="background-color: #F4F4F7">
                                    <div>
                                        <span data-answer="">You have </span><span class="badge bg-danger">{{$totalMessages}}</span><span data-answer=""> unread messages. <a href="/account">Activate</a> your plan and view your inbox.</span>
                                    </div>
                                </div>
                                @endif
                                @endfreeUser
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/6.10.0/css/flag-icons.min.css"
          integrity="sha512-ZQKxM5Z+PmOVa/VmISvHcqlUgzPejY92+I+sur69qiB7Vd+dAaDNMwy7AnRr6HcFbYY4so1FFPBgugE5s2jm7Q=="
          crossorigin="anonymous" referrerpolicy="no-referrer"/>

    <template id="template">
        <div class="d-flex justify-content-between align-items-start mb-3 border p-3 rounded message-item"
             data-message-uid="">
            <div><strong>Human:</strong> <span data-question></span> <br>
                <strong>Bot:</strong> <span data-answer></span>
                <div class="mt-2">
                    <small class="text-muted"> IP: <span data-ip></span> | Time: <span
                            data-time></span> | <span data-country
                                                      class="fi fi-cc"></span> </small></div>
            </div>
            <div class="message-buttons flex-shrink-0"></div>
        </div>
    </template>

    <template id="buttons-inbox">
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="flag" @endpaidUser title="Flag"><i class="bi bi-flag"></i></button>
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="archive" @endpaidUser title="Archive"><i class="bi bi-archive"></i>
        </button>
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="delete" @endpaidUser data-tab-type="inbox" title="Delete"><i class="bi bi-x"></i></button>
    </template>

    <template id="buttons-archived">
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="flag" @endpaidUser title="Flag"><i class="bi bi-flag"></i></button>
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="unarchive" @endpaidUser title="Unarchive"><i
                class="bi bi-box-arrow-in-up"></i></button>
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="delete" @endpaidUser title="Delete"><i class="bi bi-x"></i></button>
    </template>

    <template id="buttons-flagged">
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="unflag" @endpaidUser title="Unflag"><i class="bi bi-flag-fill"></i>
        </button>
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="archive" @endpaidUser title="Archive"><i class="bi bi-archive"></i>
        </button>
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="delete" @endpaidUser title="Delete"><i class="bi bi-x"></i></button>
    </template>

    <template id="buttons-deleted">
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="restore" @endpaidUser title="Restore"><i
                class="bi bi-arrow-counterclockwise"></i></button>
        <button @paid class="btn btn-light btn-sm" @paidUser data-action="permanently_delete" @endpaidUser title="Permanently Delete"><i
                class="bi bi-trash-fill"></i></button>
    </template>

    <script>
        $(document).ready(function() {

            $(document).on('click', '[data-action]', function(e) {
                e.preventDefault();
                const action = $(this).data('action');
                const tabType = $(this).data('tab-type') || 'inbox';
                const $message = $(this).closest('[data-message-uid]');
                const messageUid = $message.data('message-uid');
                $.post(`{{route('messages.action')}}`, {action, message_uuid: messageUid, _token: '{{csrf_token()}}', _method: 'PUT'}).done(function() {
                    if(['delete', 'permanently_delete'].includes(action)) {
                        $message.remove();
                    }

                    if(tabType === 'inbox') {
                      $message.remove();
                      const $badge = $('.nav-link.active .badge').eq(0);
                      let number = parseInt($badge.text()) - 1;

                      if(number < 0) {
                        number = 0
                      }

                      $badge.text(number);
                    }
                });
            });

            function loadMessages(type) {

                const container = document.querySelector('.tab-pane.active');
                container.innerHTML = '';

                $.get(`{{route('messages.get')}}?type=${type}`, function(data) {

                    const template = document.getElementById('template');
                    const content = template.content;
                    const buttonsTemplate = document.getElementById(`buttons-${type}`).innerHTML;

                    data.data.forEach((message) => {
                        const t = content.cloneNode(true);
                        t.querySelector('[data-message-uid]').dataset.messageUid = message.message_uuid;
                        t.querySelector('[data-question]').innerHTML = message.question;
                        t.querySelector('[data-answer]').innerHTML = message.answer;
                        t.querySelector('[data-ip]').innerHTML = message.ip_address;
                        t.querySelector('[data-time]').innerHTML = message.created_at;
                        t.querySelector('.message-buttons').innerHTML = buttonsTemplate;

                        if(message.country_code) {
                          t.querySelector('[data-country]').classList.add(`fi-${message.country_code.toLowerCase()}`);
                        } else {
                          t.querySelector('[data-country]').remove();
                        }

                        container.appendChild(t);
                    });
                });
            }

            $('[data-tab-type]').click(function(e) {
                loadMessages($(this).data('tab-type'));
            });

            loadMessages('inbox');

        });
    </script>
@endpush
