@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $currentType = $chatConfig->type ?? \App\Models\ChatConfig::TYPE_FAQ;
@endphp
@extends('layouts.dashboard')

@section('page-title', 'Knowledge Base')

@section('content')
    <div class="dashboard-card">
        <div class="dashboard-card-header">
            <h2 class="dashboard-card-title">Train Your ChatBot</h2>
        </div>

        <form action="{{route('knowledge')}}" id="knowledge-form" method="post" enctype="multipart/form-data">
            @csrf

            <!-- Tabs Navigation -->
            <ul class="nav nav-tabs mb-4" id="knowledgeTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $currentType === 'faq' ? 'active' : '' }}" id="faq-tab" data-bs-toggle="tab" data-bs-target="#faq-content" type="button" role="tab" data-type="faq">
                        <i class="bi bi-question-circle me-1"></i> FAQ's
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $currentType === 'plainText' ? 'active' : '' }}" id="plaintext-tab" data-bs-toggle="tab" data-bs-target="#plaintext-content" type="button" role="tab" data-type="plainText">
                        <i class="bi bi-file-text me-1"></i> Plain Text
                    </button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link {{ $currentType === 'pdf' ? 'active' : '' }}" id="pdf-tab" data-bs-toggle="tab" data-bs-target="#pdf-content" type="button" role="tab" data-type="pdf">
                        <i class="bi bi-file-pdf me-1"></i> Upload PDF
                    </button>
                </li>
            </ul>

            <input type="hidden" name="type" id="knowledge-type" value="{{ $currentType }}">

            <!-- Tab Content -->
            <div class="tab-content" id="knowledgeTabContent">
                <!-- FAQ Tab -->
                <div class="tab-pane fade {{ $currentType === 'faq' ? 'show active' : '' }}" id="faq-content" role="tabpanel">
                    <p class="text-muted mb-3">FAQ's are the easiest way to train your AI support agent. Add questions and answers below.</p>

                    <div id="faqContainer">
                        <div class="mb-3">
                            <button type="button" id="addFaq" class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-plus-lg me-1"></i> Add Question
                            </button>
                        </div>

                        @forelse($chatConfig?->items ?? [] as $item)
                            <div class="faqPair border rounded p-3 mb-3" style="background: var(--bg-cream);">
                                <div class="d-flex justify-content-end mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger deleteFaq">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Question</label>
                                    <input type="text" class="form-control question" placeholder="Enter your question" value="{{$item['q']}}" name="q[]">
                                </div>
                                <div>
                                    <label class="form-label small text-muted">Answer</label>
                                    <textarea rows="2" class="form-control answer" placeholder="Enter the answer" name="a[]">{{$item['a']}}</textarea>
                                </div>
                            </div>
                        @empty
                            <div class="faqPair border rounded p-3 mb-3" style="background: var(--bg-cream);">
                                <div class="d-flex justify-content-end mb-2">
                                    <button type="button" class="btn btn-sm btn-outline-danger deleteFaq">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label small text-muted">Question</label>
                                    <input type="text" class="form-control question" placeholder="Enter your question" value="" name="q[]">
                                </div>
                                <div>
                                    <label class="form-label small text-muted">Answer</label>
                                    <textarea rows="2" class="form-control answer" placeholder="Enter the answer" name="a[]"></textarea>
                                </div>
                            </div>
                        @endforelse
                    </div>
                </div>

                <!-- Plain Text Tab -->
                <div class="tab-pane fade {{ $currentType === 'plainText' ? 'show active' : '' }}" id="plaintext-content" role="tabpanel">
                    <p class="text-muted mb-3">Enter plain text information about your business, products, or services.</p>
                    <textarea class="form-control" id="plainTextInput" rows="15" name="text" placeholder="Enter your plain text here...">{{ $plainText ?? '' }}</textarea>
                </div>

                <!-- PDF Tab -->
                <div class="tab-pane fade {{ $currentType === 'pdf' ? 'show active' : '' }}" id="pdf-content" role="tabpanel">
                    <p class="text-muted mb-3">Upload a PDF document to train your chatbot.</p>
                    <div class="border rounded p-4 text-center" style="background: var(--bg-cream); border-style: dashed !important;">
                        <i class="bi bi-cloud-upload" style="font-size: 48px; color: var(--text-secondary);"></i>
                        <p class="mt-2 mb-3">Drag and drop your PDF here, or click to browse</p>
                        <input type="file" name="file" class="form-control" accept=".pdf" style="max-width: 300px; margin: 0 auto;">
                    </div>
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-check-lg me-1"></i> Save Knowledge Base
                </button>
            </div>
        </form>
    </div>

    <!-- Switch Type Confirmation Modal -->
    <div class="modal fade" id="switchTypeModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Switch Knowledge Type</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p id="switchModalText">You are switching knowledge types. Your chatbot will only use information from the selected type. No information will be lost from other sections.</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-primary" id="confirmSwitch">Switch & Save</button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" />

    <script>
        $(function() {
            var currentType = '{{ $currentType }}';
            var pendingType = null;
            var $switchModal = new bootstrap.Modal(document.getElementById('switchTypeModal'));

            // Tab click handler
            $('#knowledgeTabs button[data-bs-toggle="tab"]').on('click', function(e) {
                var newType = $(this).data('type');

                if (newType !== currentType) {
                    e.preventDefault();
                    e.stopPropagation();
                    pendingType = newType;

                    @freeUser
                    if (newType !== 'faq') {
                        window.showUpgradeModal();
                        return;
                    }
                    @endfreeUser

                    $('#switchModalText').text('You are switching to ' + getTypeName(newType) + '. Your chatbot will only use information from this section. No information will be lost from other sections.');
                    $switchModal.show();
                }
            });

            // Confirm switch
            $('#confirmSwitch').on('click', function() {
                if (pendingType) {
                    currentType = pendingType;
                    $('#knowledge-type').val(currentType);

                    // Activate the tab
                    var $tab = $('[data-type="' + pendingType + '"]');
                    bootstrap.Tab.getOrCreateInstance($tab[0]).show();

                    $switchModal.hide();

                    // Auto-submit to save the type change
                    setTimeout(function() {
                        $('#knowledge-form').submit();
                    }, 300);
                }
            });

            function getTypeName(type) {
                switch(type) {
                    case 'faq': return "FAQ's";
                    case 'plainText': return 'Plain Text';
                    case 'pdf': return 'PDF Upload';
                    default: return type;
                }
            }

            // Add FAQ
            $('#addFaq').on('click', function() {
                var newFaq = `
                    <div class="faqPair border rounded p-3 mb-3" style="background: var(--bg-cream);">
                        <div class="d-flex justify-content-end mb-2">
                            <button type="button" class="btn btn-sm btn-outline-danger deleteFaq">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Question</label>
                            <input type="text" class="form-control question" placeholder="Enter your question" name="q[]">
                        </div>
                        <div>
                            <label class="form-label small text-muted">Answer</label>
                            <textarea rows="2" class="form-control answer" placeholder="Enter the answer" name="a[]"></textarea>
                        </div>
                    </div>
                `;
                $('#faqContainer').append(newFaq);
            });

            // Delete FAQ
            $(document).on('click', '.deleteFaq', function() {
                $(this).closest('.faqPair').remove();
            });

            // Auto-save on blur
            $(document).on('blur', '.answer, .question', function() {
                var $parent = $(this).closest('.faqPair');
                var q = $parent.find('.question').val().trim();
                var a = $parent.find('.answer').val().trim();

                if (q.length && a.length) {
                    saveFaqChanges();
                }
            });

            function saveFaqChanges() {
                var $form = $('#knowledge-form');
                $.post($form.attr('action'), $form.serialize(), function(data) {
                    if (data.success) {
                        $.toast({
                            heading: 'Saved',
                            text: 'Knowledge base updated.',
                            icon: 'success',
                            position: 'bottom-center',
                            hideAfter: 2000
                        });
                    }
                });
            }
        });
    </script>
@endpush
