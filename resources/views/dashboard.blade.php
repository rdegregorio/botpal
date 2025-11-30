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
        <div class="row align-items-center">
            <div class="col-lg-8 col-md-7 mb-3">
                <div class="input-group">
                    <input type="text" class="form-control" name="open_ai_token" id="open_ai_token" autocomplete="off"
                           placeholder="{{ Auth::user()->open_ai_token ? 'API Key saved (click Edit to change)' : 'Enter your OpenAI API key (sk-proj-...)' }}">
                    <button class="btn btn-primary" type="button" id="saveApiKey">
                        <i class="bi bi-check-lg me-1"></i> Save
                    </button>
                </div>
                @if(Auth::user()->open_ai_token)
                    <small class="text-success mt-1 d-block"><i class="bi bi-check-circle me-1"></i> API Key configured</small>
                @endif
            </div>
            <div class="col-lg-4 col-md-5 mb-3">
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
            // Save API Key button
            $('#saveApiKey').on('click', function() {
                var $btn = $(this);
                var $input = $('#open_ai_token');
                var value = $input.val().trim();

                if (!value) {
                    alert('Please enter your OpenAI API key');
                    return;
                }

                $btn.prop('disabled', true).html('<i class="bi bi-hourglass-split me-1"></i> Saving...');

                $.post('{{route('account.update')}}', {
                    field: 'open_ai_token',
                    value: value,
                    _token: '{{csrf_token()}}'
                }, function (res) {
                    $btn.prop('disabled', false).html('<i class="bi bi-check-lg me-1"></i> Save');
                    $input.val('').attr('placeholder', 'API Key saved (click Edit to change)');

                    // Add success indicator if not already there
                    if (!$input.parent().next('.text-success').length) {
                        $input.closest('.input-group').after('<small class="text-success mt-1 d-block"><i class="bi bi-check-circle me-1"></i> API Key configured</small>');
                    }

                    alert('API Key saved successfully!');
                }).fail(function (err) {
                    $btn.prop('disabled', false).html('<i class="bi bi-check-lg me-1"></i> Save');
                    alert('Error: ' + (err.responseJSON?.message || 'Failed to save API key'));
                });
            });

            // Model selection
            $(document).on('change', '[name=open_ai_model]', function () {
                var $option = $(this).closest('.model-option');
                $('.model-option').removeClass('border-dark bg-light');
                $option.addClass('border-dark bg-light');

                $.post('{{route('account.update')}}', {
                    field: 'open_ai_model',
                    value: $(this).val(),
                    _token: '{{csrf_token()}}'
                }).fail(function (err) {
                    alert('Error: ' + (err.responseJSON?.message || 'Failed to save model'));
                });
            });
        });
    </script>
@endpush
