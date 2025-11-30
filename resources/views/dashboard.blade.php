@php
    /** @var \App\Models\ChatConfig $chatConfig */
@endphp
@extends('layouts.dashboard')

@section('page-title', 'ChatBot Setup')

@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title">Choose Your Character</h2>
        </div>
        <div class="mb-4" style="max-width: 65em;">
            @include('_characters')
        </div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title">Your Service Description</h2>
        </div>
        <p class="mb-3" style="color: var(--text-secondary); font-size: 14px;">Give a summary of the service/product you provide:</p>
        <form action="{{route('dashboard.update-chat-config')}}" method="post">
            @csrf
            <div class="form-group mb-4">
                <textarea name="general_prompt" class="form-control" id="exampleTextarea" rows="5" placeholder="e.g. Our company is called Lumina. We specialize in artisanal candles, handcrafted to perfection. Based in the US, Lumina offers a diverse range of candles, from enchanting scents to timeless classics. Each candle promises to illuminate your space with elegance and warmth. Dive into a world of ambient glow and mesmerizing aromas with Lumina.">{{old('general_prompt', $chatConfig?->general_prompt)}}</textarea>
            </div>
            <input type="hidden" name="character" value="{{$chatConfig?->character}}">
            <div class="text-end">
                <button type="submit" class="btn btn-primary">Save & Continue</button>
            </div>
        </form>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-key me-2"></i>OpenAI API Key</h2>
        </div>
        <p class="mb-3" style="color: var(--text-secondary); font-size: 14px;">Enter your OpenAI API key to power your chatbot.</p>
        <div class="row align-items-end">
            <div class="col-lg-9 col-md-8">
                <div class="form-group mb-3">
                    <div data-editable>
                        <input type="text" readonly autocomplete="off" class="form-control" name="open_ai_token" id="open_ai_token"
                               placeholder="{{Auth::user()->open_ai_token ? '••••••••••••••••••••' : 'sk-proj-...'}}">
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-4 mb-3">
                <a href="https://platform.openai.com/api-keys" class="btn btn-outline-secondary w-100" target="_blank">
                    <i class="bi bi-box-arrow-up-right me-1"></i> Get API Key
                </a>
            </div>
        </div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-cpu me-2"></i>OpenAI Model</h2>
        </div>
        <p class="mb-3" style="color: var(--text-secondary); font-size: 14px;">Select the AI model for your chatbot.</p>
        <div class="row">
            @foreach(\App\Services\OpenAIService::AVAILABLE_MODELS as $modelKey => $modelLabel)
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="form-check model-option p-3 border rounded {{ Auth::user()->open_ai_model === $modelKey ? 'border-dark bg-light' : '' }}">
                    <input class="form-check-input" type="radio" name="open_ai_model" id="model_{{ $loop->index }}"
                           value="{{ $modelKey }}" {{ Auth::user()->open_ai_model === $modelKey ? 'checked' : '' }}>
                    <label class="form-check-label d-block" for="model_{{ $loop->index }}" style="cursor: pointer;">
                        <strong>{{ explode(' - ', $modelLabel)[0] }}</strong>
                        <small class="d-block text-muted">{{ explode(' - ', $modelLabel)[1] ?? '' }}</small>
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>
@endsection

@push('bottom')
    <script>
        // Initialize Bootstrap Tooltip with placement bottom
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl, {
                placement: 'bottom'
            })
        })
    </script>
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            let images = document.querySelectorAll('.image-character');
            const character = document.querySelector('[name="character"]');

            images.forEach(function(img) {
                img.addEventListener('click', function(e) {
                    @freeUser
                        if(e.clientX && !img.classList.contains('selected-image')) {
                            window.showUpgradeModal();
                            return;
                        }
                    @endfreeUser
                    // Remove 'selected-image' class from all images
                    images.forEach(function(innerImg) {
                        innerImg.classList.remove('selected-image');
                    });

                    // Add 'selected-image' class to the clicked image
                    img.classList.add('selected-image');
                    character.value = img.getAttribute('data-character');
                });
            });

            @if($chatConfig?->character)
                $('[data-character={{$chatConfig->character}}]').trigger('click');
            @endif

            @freeUser
                $('[data-character=3]').trigger('click');
            @endfreeUser
        });
    </script>
    <script>
        // OpenAI Key and Model handling
        $(function() {
            // Editable fields
            $('input:read-only').click(function () {
                $(this).closest('[data-editable]')?.find('[data-edit]')?.click();
            });

            $('[data-editable]').mouseover(function () {
                if ($(this).find('.edit-buttons').length) return;

                var $buttons = $('<div class="edit-buttons"><button data-edit class="btn btn-sm btn-primary">Edit</button></div>');
                $(this).append($buttons);
            });

            $('[data-editable]').mouseleave(function () {
                if ($(this).data('edit-mode')) return;
                $(this).find('.edit-buttons').remove();
            });

            $(document).on('click', '[data-editable] [data-edit]', function () {
                var $block = $(this).closest('[data-editable]');
                $block.data('edit-mode', true);
                $block.find('input').prop('readonly', false).focus();

                var buttons = '<button data-save class="btn btn-sm btn-success">Save</button> ' +
                    '<button data-cancel class="btn btn-sm btn-secondary">Cancel</button>';
                $block.find('.edit-buttons').html(buttons);
            });

            $(document).on('click', '[data-editable] [data-cancel]', function () {
                var $block = $(this).closest('[data-editable]');
                $block.data('edit-mode', false);
                $block.find('input').prop('readonly', true).val('');
                $block.find('.edit-buttons').remove();
            });

            $(document).on('click', '[data-editable] [data-save]', function () {
                var $block = $(this).closest('[data-editable]');
                $block.data('edit-mode', false);
                $block.find('input').prop('readonly', true);

                var field = $block.find('input').attr('name');
                var data = {
                    field: field,
                    value: $block.find('input').val(),
                    _token: '{{csrf_token()}}'
                };

                $.post('{{route('account.update')}}', data, function (res) {
                    if (res) {
                        $block.find('.edit-buttons').remove();
                        if (field === 'open_ai_token') {
                            $block.find('input').attr('placeholder', '••••••••••••••••••••').val('');
                        }
                    }
                }).fail(function (err) {
                    alert('Error: ' + (err.responseJSON?.message || 'Something went wrong'));
                    $block.find('input').prop('readonly', false);
                    $block.data('edit-mode', true);
                });
            });

            // Model selection
            $(document).on('change', '[name=open_ai_model]', function () {
                var $option = $(this).closest('.model-option');
                $('.model-option').removeClass('border-dark bg-light');
                $option.addClass('border-dark bg-light');

                var data = {
                    field: 'open_ai_model',
                    value: $(this).val(),
                    _token: '{{csrf_token()}}'
                };

                $.post('{{route('account.update')}}', data, function (res) {
                    if (!res) {
                        alert('Error saving model selection');
                    }
                }).fail(function (err) {
                    alert('Error: ' + (err.responseJSON?.message || 'Something went wrong'));
                });
            });
        });
    </script>
@endpush
