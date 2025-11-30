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
@endpush
