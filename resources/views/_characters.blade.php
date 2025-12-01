<div class="row justify-content-center">
    @foreach([
            'Ava' => 1,
            'Alex' => 2,
            'Ben' => 3,
            'Mary' => 4,
            'Roy' => 6,
            'Dana' => 7,
            'Ana' => 8,
            'Leo' => 9,
            'Lisa' => 10,
            'Max' => 11,
            'Zoe' => 12,
        ] as $name => $img)
        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
            <picture>
                <source media="(width: 1200px)" srcset="/images/ch{{$img}}.png">
                <source media="(max-width: 1200px)" srcset="/images/ch{{$img}}lg.png">
                <img src="/images/ch{{$img}}lg.png" alt="Image {{$img}}"
                     class="rounded-circle image-hover image-character mx-auto d-block"
                     data-bs-toggle="tooltip" title="I am {{$name}}" data-character="{{$img}}"
                     data-character-name="{{$name}}"
                     style="width: 100px; height: 100px;"/>
            </picture>
        </div>
    @endforeach
    @php($customCharacter = $chatConfig?->custom_character ?? $chatConfig?->character)
    @if($customCharacter && !is_numeric($customCharacter))
        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
            <picture>
                <img src="/storage/{{$customCharacter}}" alt="Image"
                     class="rounded-circle image-hover image-character mx-auto d-block add-character"
                     data-bs-toggle="tooltip" title="Own character" data-character="{{$customCharacter}}"
                     data-character-name="Custom character"
                     style="width: 100px; height: 100px;"/>
            </picture>
        </div>
    @elseif(request()->routeIs('settings'))
        <div class="col-xl-2 col-lg-3 col-md-3 col-sm-4 col-6">
            <picture>
                <img @paid src="/site-icons/plus.svg" alt="Add character" class="image-hover add-character mx-auto d-block"
                     data-bs-toggle="tooltip" title="Add own character"
                     style="width: 100px; height: 100px; cursor: pointer;"/>
            </picture>
        </div>
    @endif

    @push('bottom')
        @paidUser
        <div class="modal fade" id="uploadCharacterModal" tabindex="-1" aria-labelledby="uploadCharacterModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <!-- Use modal-lg for a larger modal -->
                <div class="modal-content">
                    <form id="image-upload-form" action="{{route('dashboard.upload-character-image')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="modal-header">
                            <div class="modal-title">
                                Please upload your character image
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                        </div>
                        <div class="modal-body d-flex flex-column" style="height: 400px;">
                            <div class="form-group">
                                <label for="image-file">Choose Image (only 500x500px)</label>
                                <input type="file" name="file" class="form-control-file" id="image-file">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-default" data-bs-dismiss="modal">Close</button>
                            <button type="submit" class="btn btn-primary">Upload</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <script>
          $('.add-character').click(function(e) {
            $('#uploadCharacterModal').modal('show');
          });
        </script>
        @endpaidUser
    @endpush
</div>
