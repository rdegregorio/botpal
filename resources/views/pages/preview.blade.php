@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $chatConfig = Auth::user()->chatConfigLatest;
@endphp

@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2">
        <div class="container">
            <div class="row col-lg-10 col-12 mx-auto">
                <div class="d-flex justify-content-between align-items-center">
                    <h4 class="mb-0"><i class="bi bi-eye"></i> Live Preview</h4>
                </div>

                <div id="chat-box"></div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script async src="{{route('api.chat.embed', $chatConfig?->uuid)}}?blockId=chat-box"></script>
    <style>
        #chat-box {
            border: 1px solid #ccc;
            border-radius: 10px;
            margin-top: 20px;
        }
        #chat-wrapper #avatar, .chat-dialog .chat-close {
            display: none !important;
        }
    </style>
@endpush
