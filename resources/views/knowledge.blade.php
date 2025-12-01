@php
    /** @var \App\Models\ChatConfig $chatConfig */
    $currentType = $chatConfig?->type ?? \App\Models\ChatConfig::TYPE_FAQ;
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
                    <p class="text-muted mb-3">FAQ's are the easiest way to train your AI support agent.</p>

                    <div id="faqContainer">
                        <div class="text-left mb-3">
                            <button type="button" id="addFaq" class="btn btn-lg addFaq" title="Add Question">
                                <i class="bi bi-plus-lg"></i>
                            </button>
                        </div>

                        @forelse($chatConfig?->items ?? [] as $item)
                            <div class="faqPair mb-1 mt-2">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-sm deleteFaq"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="input-group mb-2 mt-2">
                                    <span class="input-group-text">Q</span>
                                    <input type="text" class="form-control question" placeholder="Enter your question" value="{{$item['q']}}" name="q[]">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">A</span>
                                    <textarea rows="1" class="form-control answer" placeholder="Enter the answer" name="a[]">{{$item['a']}}</textarea>
                                </div>
                                <hr>
                            </div>
                        @empty
                            <div class="faqPair mb-1 mt-2">
                                <div class="d-flex justify-content-end align-items-center">
                                    <button type="button" class="btn btn-sm deleteFaq"><i class="bi bi-x-lg"></i></button>
                                </div>
                                <div class="input-group mb-2 mt-2">
                                    <span class="input-group-text">Q</span>
                                    <input type="text" class="form-control question" placeholder="Enter your question" value="" name="q[]">
                                </div>
                                <div class="input-group mb-2">
                                    <span class="input-group-text">A</span>
                                    <textarea rows="1" class="form-control answer" placeholder="Enter the answer" name="a[]"></textarea>
                                </div>
                                <hr>
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
                    <input type="file" name="file" class="form-control" accept=".pdf">
                </div>
            </div>

            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Save</button>
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
            $('#addFaq').on('click', function(e) {
                e.preventDefault();
                var newFaq = `
                    <div class="faqPair mb-1 mt-2">
                        <div class="d-flex justify-content-end align-items-center">
                            <button type="button" class="btn btn-sm deleteFaq"><i class="bi bi-x-lg"></i></button>
                        </div>
                        <div class="input-group mb-2 mt-2">
                            <span class="input-group-text">Q</span>
                            <input type="text" class="form-control question" placeholder="Enter your question" name="q[]">
                        </div>
                        <div class="input-group mb-2">
                            <span class="input-group-text">A</span>
                            <textarea rows="1" class="form-control answer" placeholder="Enter the answer" name="a[]"></textarea>
                        </div>
                        <hr>
                    </div>
                `;
                $('#faqContainer').append(newFaq);
            });

            // Delete FAQ
            $(document).on('click', '.deleteFaq', function(e) {
                e.preventDefault();
                $(this).closest('.faqPair').remove();
                saveFaqChanges();
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

            // Auto-resize textareas
            $(document).on('input', 'textarea.answer', function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });

            $('textarea.answer').each(function() {
                this.style.height = 'auto';
                this.style.height = (this.scrollHeight) + 'px';
            });
        });
    </script>
@endpush
