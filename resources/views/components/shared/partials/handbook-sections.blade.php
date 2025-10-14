@foreach($sections as $section)
    <div class="handbook-entry" id="section-{{ $section->id }}">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="sub-header mt-4">{{ $section->header }}</h5>
                @if(Auth::user()->role === 'super')
                    <button class="btn btn-sm btn-outline-primary edit-section-btn" data-id="{{ $section->id }}" data-header="{{ $section->header }}" data-description="{{ $section->description }}">
                    Edit
                    </button>
                @endif           
        </div>

        @php
            $paragraphs = preg_split('/\r\n|\r|\n/', $section->description);
        @endphp

        @foreach($paragraphs as $paragraph)
            @if(trim($paragraph) !== '')
                <p>{{ $paragraph }}</p>
            @endif
        @endforeach
    </div>
@endforeach
