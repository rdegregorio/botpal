@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $selectedModel = $chatConfig?->getSettings(\App\Models\ChatConfig::SETTINGS_AI_MODEL) ?? config('services.openai.default_model', 'gpt-5-mini');
@endphp
@extends('layouts.dashboard')

@section('page-title', 'ChatBot Setup')

@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-cpu me-2"></i>AI Model</h2>
        </div>
        <p class="mb-3" style="color: var(--text-secondary); font-size: 14px;">Select the AI model for your chatbot.</p>
        <div class="row">
            @foreach(\App\Services\OpenAIService::AVAILABLE_MODELS as $modelKey => $modelLabel)
            <div class="col-lg-4 col-md-6 mb-3">
                <div class="form-check model-option p-3 border rounded {{ $selectedModel === $modelKey ? 'border-dark bg-light' : '' }}" style="cursor: pointer;">
                    <input class="form-check-input" type="radio" name="ai_model" id="model_{{ $loop->index }}"
                           value="{{ $modelKey }}" {{ $selectedModel === $modelKey ? 'checked' : '' }}>
                    <label class="form-check-label d-block" for="model_{{ $loop->index }}" style="cursor: pointer;">
                        <strong>{{ explode(' - ', $modelLabel)[0] }}</strong>
                        <small class="d-block text-muted">{{ explode(' - ', $modelLabel)[1] ?? '' }}</small>
                    </label>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-code-slash me-2"></i>Chat Widget Code</h2>
        </div>
        <p class="mb-3" style="color: var(--text-secondary); font-size: 14px;">Copy and paste this code into your website to add the chatbot.</p>
        <div class="code-container position-relative">
            <pre id="chatCodeBox" class="code-box p-3 rounded" style="background: #f5f5f5; border: 1px solid #ddd; cursor: pointer;" readonly>@if(!$chatConfig?->uuid)First update your chat config.@else&lt;!-- Start of iamsam.ai Embed Code--&gt;
&lt;script async src=&quot;{{route('api.chat.embed', $chatConfig->uuid)}}&quot;&gt;&lt;/script&gt;
&lt;!-- End of iamsam.ai Embed Code --&gt;@endif</pre>
            <button class="btn btn-sm btn-outline-secondary" style="position: absolute; top: 10px; right: 10px;" onclick="copyCode()" title="Copy Code">
                <i class="bi bi-clipboard"></i> Copy
            </button>
        </div>
    </div>

    @php($copyrightEnabled = $chatConfig?->getSettings(\App\Models\ChatConfig::SETTINGS_COPYRIGHT_ENABLED) ?? true)
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title"><i class="bi bi-badge-cc me-2"></i>Powered By</h2>
        </div>
        <div class="form-check">
            <input class="form-check-input" type="checkbox" id="poweredByCheckbox" @checked($copyrightEnabled)>
            <label class="form-check-label" for="poweredByCheckbox">
                Show "Created your own AI chatbot with iamsam.ai" branding
            </label>
        </div>
        <small class="text-muted d-block mt-2">Uncheck to remove branding (available on paid plans)</small>
    </div>
@endsection

@push('bottom')
    <script>
        // Copy embed code function
        function copyCode() {
            var codeBox = document.getElementById('chatCodeBox');
            var textToCopy = codeBox.textContent || codeBox.innerText;

            navigator.clipboard.writeText(textToCopy.trim()).then(function() {
                var btn = codeBox.nextElementSibling;
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

        $(function() {
            // Model selection
            $(document).on('change', '[name=ai_model]', function () {
                var $option = $(this).closest('.model-option');
                $('.model-option').removeClass('border-dark bg-light');
                $option.addClass('border-dark bg-light');

                $.post('{{route('dashboard.update-chat-config')}}', {
                    'settings[{{\App\Models\ChatConfig::SETTINGS_AI_MODEL}}]': $(this).val(),
                    _token: '{{csrf_token()}}'
                }).fail(function (err) {
                    alert('Error: ' + (err.responseJSON?.message || 'Failed to save model'));
                });
            });

            // Powered By checkbox
            $('#poweredByCheckbox').on('click', function(e) {
                @freeUser
                e.preventDefault();
                window.showUpgradeModal();
                return;
                @endfreeUser

                @paidUser
                var isChecked = $(this).prop('checked') ? 1 : 0;
                $.post('{{route('dashboard.update-chat-config')}}', {
                    'settings[{{\App\Models\ChatConfig::SETTINGS_COPYRIGHT_ENABLED}}]': isChecked,
                    _token: '{{csrf_token()}}'
                });
                @endpaidUser
            });
        });
    </script>
@endpush
