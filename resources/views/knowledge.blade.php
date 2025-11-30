@php
    /** @var \App\Models\ChatConfig $chatConfig */
@endphp
@extends('layouts.dashboard')

@section('page-title', 'Knowledge Base')

@section('content')
    <div class="dashboard-card">
        <form action="{{route('knowledge')}}" id="knowledge-form" method="post"
              enctype="multipart/form-data">
            @csrf
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h2 class="dashboard-card-title mb-0">Train Your ChatBot</h2>

                            <div class="row ml-auto">
                                <div class="col-auto">
                                    <input type="radio" id="typeChoice1" name="type"
                                           value="{{\App\Models\ChatConfig::TYPE_FAQ}}" checked/>
                                    <label for="typeChoice1">FAQ's</label>
                                </div>
                                <div class="col-auto">
                                    <input type="radio" id="typeChoice2" name="type"
                                           value="{{\App\Models\ChatConfig::TYPE_PLAIN_TEXT}}"/>
                                    <label for="typeChoice2">Plain Text</label>
                                </div>
                                <div class="col-auto">
                                    <input type="radio" id="typeChoice3" name="type"
                                           value="{{\App\Models\ChatConfig::TYPE_PDF}}"/>
                                    <label for="typeChoice3">Upload PDF</label>
                                </div>
                            </div>
                        </div>
                        <p class="mb-3" id="descriptionText">FAQs are the easiest way to train your AI support agent.</p>
                        <div class="faqContainer" id="faqContainer">
                            <div class="text-left mb-0">
                                <button id="addFaq" class="btn btn-lg addFaq mb-3" title="Add Question"><i
                                            class="bi bi-plus-lg"></i></button>
                            </div>
                            @forelse($chatConfig?->items ?? [] as $item)
                                <div class="faqPair mb-1 mt-2">
                                    <div class="d-flex justify-content-end align-items-center">
                                        <div>
                                            <button class="btn btn-sm deleteFaq"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    </div> <!-- Rest of the question and answer inputs -->
                                    <div class="input-group mb-2 mt-2"><span class="input-group-text">Q</span> <input
                                            type="text" class="form-control question" placeholder="Enter your question"
                                            value="{{$item['q']}}" name="q[]"></div>
                                    <div class="input-group mb-2"><span class="input-group-text">A</span>
                                        <textarea rows="1"
                                             class="form-control answer"
                                            placeholder="Our candles are crafted from high-quality, natural soy wax that burns cleanly and evenly."
                                             name="a[]">{{$item['a']}}</textarea></div>
                                    <hr>
                                </div>
                            @empty
                                <div class="faqPair mb-1 mt-2">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <!-- Left aligned tag -->
                                        <div>
                                        </div> <!-- Right aligned delete button -->
                                        <div>
                                            <button class="btn btn-sm deleteFaq"><i class="bi bi-x-lg"></i></button>
                                        </div>
                                    </div> <!-- Rest of the question and answer inputs -->
                                    <div class="input-group mb-2 mt-2"><span class="input-group-text">Q</span> <input
                                            type="text" class="form-control question" placeholder="Enter your question"
                                            value="What are your candles made of?" name="q[]"></div>
                                    <div class="input-group mb-2"><span class="input-group-text">A</span>
                                        <textarea rows="1"
                                                  class="form-control answer"
                                                  placeholder="Our candles are crafted from high-quality, natural soy wax that burns cleanly and evenly."
                                                  name="a[]"></textarea></div>
                                    </div>
                                    <hr>
                                </div>
                            @endforelse
                        </div> <!-- Plain Text Section (Hidden by default) -->
                        <div class="col-lg-10 col-12  mx-auto">
                        <div id="plainTextContainer" class="training-section" style="display: none;"><textarea
                                class="form-control" id="plainTextInput" rows="15" name="text"
                                placeholder="Enter your plain text here...">{{ $plainText }}</textarea></div>
                        <!-- Upload PDF Section (Hidden by default) -->
                        <div id="pdfUploadContainer" class="training-section" style="display: none;"><input type="file" name="file"
                                                                                                            class="form-control"
                                                                                                            accept=".pdf">
                        </div>
            <div class="text-end mt-4">
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
@endsection

@push('bottom')
    <div class="modal fade" id="knowledgeSwitchModal" tabindex="-1" aria-labelledby="knowledgeSwitchModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <!-- Use modal-lg for a larger modal -->
            <div class="modal-content">
                <div class="modal-header">
                    <div class="modal-title">

                    </div> <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body d-flex flex-column" style="height: 400px;">

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                    <button type="button" onclick="confirmKnowledgeSwitch()" class="btn btn-primary">Ok</button>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.getElementById('addFaq').addEventListener('click', function(e) {
            e.preventDefault(); // Prevent any default button action

            // Create new input fields for question and answer
            let newFaqPair = document.createElement('div');
            newFaqPair.className = 'faqPair mb-1';

            let deleteWrapper = document.createElement('div');
            deleteWrapper.className = 'text-end mb-1';

            let deleteButton = document.createElement('button');
            deleteButton.className = 'btn btn-sm deleteFaq';
            deleteButton.style.backgroundColor = '#FF8080';
            deleteButton.style.fontSize = '0.6rem'; // Make the "X" even smaller
            deleteButton.innerHTML = '<i class="bi bi-x-lg"></i>';

            deleteWrapper.appendChild(deleteButton);

            let questionInputGroup = document.createElement('div');
            questionInputGroup.className = 'input-group mb-2';

            let questionLabel = document.createElement('span');
            questionLabel.className = 'input-group-text';
            questionLabel.innerText = 'Q';  // Just "Question" without numbering

            let newQuestion = document.createElement('input');
            newQuestion.type = 'text';
            newQuestion.name = 'q[]';
            newQuestion.className = 'form-control question';
            newQuestion.placeholder = 'Enter your question';

            questionInputGroup.appendChild(questionLabel);
            questionInputGroup.appendChild(newQuestion);

            let answerInputGroup = document.createElement('div');
            answerInputGroup.className = 'input-group mb-2';

            let answerLabel = document.createElement('span');
            answerLabel.className = 'input-group-text';
            answerLabel.innerText = 'A';  // Just "Answer" without numbering

            let newAnswer = document.createElement('textarea');
            newAnswer.type = 'text';
            newAnswer.name = 'a[]';
            newAnswer.className = 'form-control answer';
            newAnswer.placeholder = 'Enter the answer';

            answerInputGroup.appendChild(answerLabel);
            answerInputGroup.appendChild(newAnswer);

            let hr = document.createElement('hr');

            newFaqPair.appendChild(deleteWrapper);
            newFaqPair.appendChild(questionInputGroup);
            newFaqPair.appendChild(answerInputGroup);
            newFaqPair.appendChild(hr);

            // Insert the new input fields before the "+" button
            let addFaqButtonDiv = document.getElementById('addFaq').parentElement;
            faqContainer.insertBefore(newFaqPair, addFaqButtonDiv.nextSibling);

        });

        $(document).on('click', '.deleteFaq', function() {
            $(this).closest('.faqPair').remove();
            saveFaqChanges();
        });
    </script>
    <script>
      const $modal = $('#knowledgeSwitchModal');
      function onTypeSelectorChange(value) {
        // Hide all sections
        document.querySelectorAll('.training-section, .faqContainer').forEach(section => {
          section.style.display = 'none';
        });

        // Update the description text and show the appropriate section based on the selection
        switch (value) {
          case 'faq':
            document.getElementById(
                'descriptionText').innerText = 'FAQ\'s are the easiest way to train your AI support agent.';
            document.getElementById('faqContainer').style.display = 'block';
            break;
          case 'plainText':
            document.getElementById('descriptionText').innerText = 'Enter plain text: ';
            document.getElementById('plainTextContainer').style.display = 'block';
            break;
          case 'pdf':
            document.getElementById('descriptionText').innerText = 'Upload your PDF file:';
            document.getElementById('pdfUploadContainer').style.display = 'block';
            break;
        }
      }

      function showTypeSelectorModal(value) {

        let modalTitle = 'You are switching to ';
        let modalBody;

        // Update the description text and show the appropriate section based on the selection
        switch (value) {
          case 'faq':
            modalTitle += 'FAQ\'s';
            modalBody = 'You are switching to FAQ\'s, this means your chatbot will only take information on the FAQ section and not Plain Text or PDF. No information will be lost on the other sections.';
            break;
          case 'plainText':
            modalTitle += 'Plain Text';
            modalBody = 'You are switching to Plain Text, this means your chatbot will only take information on the Plain Text section and not FAQ’s or PDF. No information will be lost on the other sections.';
            break;
          case 'pdf':
            modalTitle += 'PDF';
            modalBody = 'You are switching to PDF, this means your chatbot will only take information on the PDF section and not FAQ’s or Plain Text. No information will be lost on the other sections.';
            break;
        }

          $modal.find('.modal-title').text(modalTitle);
          $modal.find('.modal-body').text(modalBody);
          $modal.modal('show').on('hidden.bs.modal');
      }

      let currentType;
      let latestClickedType;

        $(function(e) {
          $('input[name="type"][value="{{ $chatConfig->type }}"]').prop('checked', true);
          onTypeSelectorChange('{{ $chatConfig->type }}');
          currentType = $('input[name="type"]:checked');

          $('input[name="type"]').on('change', function() {
            currentType.prop('checked', true);
            latestClickedType = $(this);
            const value = latestClickedType.val();

            @freeUser
              if(value !== '{{\App\Models\ChatConfig::TYPE_FAQ}}') {
                $modal.modal('hide');
                window.showUpgradeModal();
                return;
              }
            @endfreeUser
            showTypeSelectorModal(value);
          });
        });

      function confirmKnowledgeSwitch() {
        currentType = latestClickedType;
        currentType.prop('checked', true);
        onTypeSelectorChange(currentType.val());
        $modal.modal('hide');

        setTimeout(function(e) {
          $('#knowledge-form').submit();
        }, 250);
      }

    </script>
    <script>
        let currentTagsContainer = null;

        function saveFaqChanges() {
          const $form = $('#knowledge-form');

          $.post($form.attr('action'), $form.serialize(), function(data) {
            if (data.success) {
              $.toast({
                heading: 'Success',
                text: 'FAQ updated successfully.',
                icon: 'success',
                position: 'bottom-center',
              });
            }
          });
        }

        $(function() {
          $(document).on('blur', '.answer, .question', function() {
            const $parent = $(this).closest('.faqPair');
            q = $parent.find('.question').val().trim();
            a = $parent.find('.answer').val().trim();

            if(!q.length || !a.length) {
              return;
            }

            saveFaqChanges();
          });
        });

        function addTag() {
            let tagInput = document.getElementById('tagInput');

            let newTag = document.createElement('span');
            newTag.className = 'tag me-2';
            newTag.innerHTML = tagInput.value + ' <i class="bi bi-x remove-tag-icon" onclick="removeTag(this)"></i>';

            currentTagsContainer.appendChild(newTag);
            tagInput.value = '';
            var myModal = new bootstrap.Modal(document.getElementById('tagModal'));
            myModal.hide(); // close the modal after adding the tag
        }

        function removeTag(element) {
            element.parentElement.remove();
        }

        document.querySelectorAll('.add-tag-btn').forEach(button => {
            button.addEventListener('click', function() {
                currentTagsContainer = button.previousElementSibling;
            });
        });

        document.addEventListener('DOMContentLoaded', function() {
          function resize(target) {
            target.style.height = 'auto';
            target.style.height = (target.scrollHeight) + 'px';
          }

          document.addEventListener('input', function(event) {
            if (event.target.matches('textarea.answer')) {
              resize(event.target);
            }
          });

          document.querySelectorAll('textarea.answer').forEach(answer => {
            resize(answer);
          });
        });


    </script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.js" integrity="sha512-zlWWyZq71UMApAjih4WkaRpikgY9Bz1oXIW5G0fED4vk14JjGlQ1UmkGM392jEULP8jbNMiwLWdM8Z87Hu88Fw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-toast-plugin/1.3.2/jquery.toast.min.css" integrity="sha512-wJgJNTBBkLit7ymC6vvzM1EcSWeM9mmOu+1USHaRBbHkm6W9EgM0HY27+UtUaprntaYQJF75rc8gjxllKs5OIQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
@endpush
