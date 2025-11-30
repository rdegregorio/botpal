@php
    /** @var \App\Models\ChatConfig $chatConfig */
@endphp
@extends('layouts.main')

@section('content')
    <div class="content-wrapper-2 mb-4">
        <div class="container mb-4">
            <div class="row">
                <div class="col-lg-9 mx-auto">
                    @if(!Auth::user()?->getCurrentActiveSubscription())
                    <div class="steps mb-4">
                    <!--
                        <div class="circle active" onclick="location.href='{{route('register')}}'">1</div>
                        <div class="circle active">2</div>
                        <div class="circle">3</div>
                    -->
                    </div>
                    @endif
                    <h2 class="mx-auto text-center mb-4"> ChatBot Settings </h2>
                    <h5 class="mx-auto mb-1"> character </h5>
                    <p class="mb-3">Choose your character:</p>
                    <div class="container mb-4" style="max-width: 65em;">
                        @include('_characters')
                    </div>
                    <h5 class="mx-auto mb-2"> Your Service </h5>
                    <form action="{{route('dashboard.update-chat-config')}}" method="post">
                        @csrf
                    <p class="mb-3">Give a summary of the service/product you provide:</p>
                    <div class="form-group mb-4">
                        <textarea name="general_prompt" class="form-control" id="exampleTextarea" rows="5" placeholder="e.g. Our company is called Lumina. We specialize in artisanal candles, handcrafted to perfection. Based in the US, Lumina offers a diverse range of candles, from enchanting scents to timeless classics. Each candle promises to illuminate your space with elegance and warmth. Dive into a world of ambient glow and mesmerizing aromas with Lumina. ">{{old('general_prompt', $chatConfig?->general_prompt)}}</textarea>
                    </div>
{{--                    <h5 class="mx-auto mb-2"> Knowledge </h5>--}}
{{--                    <p class="mb-3">Write several FAQ's (with answers) for your chatbot.</p>--}}
{{--                    <div class="faqContainer" id="faqContainer">--}}
{{--                        @forelse($chatConfig?->items ?? [] as $item)--}}
{{--                            <div class="faqPair mb-1">--}}
{{--                                <div class="text-end mb-1"> <button class="btn btn-sm deleteFaq"> <i class="bi bi-x-lg"></i> </button> </div>--}}
{{--                                <div class="input-group mb-2"> <span class="input-group-text">Q</span> <input name="q[]" value="{{$item['q']}}" type="text" class="form-control question" placeholder="Enter your question"> </div>--}}
{{--                                <div class="input-group mb-2"> <span class="input-group-text">A</span> <input name="a[]" value="{{$item['a']}}" type="text" class="form-control answer" placeholder="Enter your answer"> </div>--}}
{{--                                <hr>--}}
{{--                            </div>--}}
{{--                        @empty--}}
{{--                        <div class="faqPair mb-1">--}}
{{--                            <div class="text-end mb-1"> <button class="btn btn-sm deleteFaq"> <i class="bi bi-x-lg"></i> </button> </div>--}}
{{--                            <div class="input-group mb-2"> <span class="input-group-text">Q</span> <input name="q[]" type="text" class="form-control question" placeholder="Enter your question"> </div>--}}
{{--                            <div class="input-group mb-2"> <span class="input-group-text">A</span> <input name="a[]" type="text" class="form-control answer" placeholder="Enter your answer"> </div>--}}
{{--                            <hr>--}}
{{--                        </div>--}}
{{--                        @endforelse--}}
{{--                        <div class="text-center mb-3"> <button id="addFaq" class="btn btn-sm addFaq"> <i class="bi bi-plus-lg"></i> </button> </div>--}}
{{--                    </div>--}}
                        <input type="hidden" name="character" value="{{$chatConfig?->character}}">
                    <div class="text-end mt-4"> <button type="submit" class="btn btn-primary">Next</button> </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('bottom')
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
            deleteButton.addEventListener('click', function() {
                newFaqPair.remove();
            });

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

            let newAnswer = document.createElement('input');
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
            document.getElementById('faqContainer').insertBefore(newFaqPair, document.getElementById('addFaq').parentElement);
        });

        // Add event listener for existing delete button
        document.querySelectorAll('.deleteFaq').forEach(button => {
            button.addEventListener('click', function() {
                button.closest('.faqPair').remove();
            });
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
@endpush
